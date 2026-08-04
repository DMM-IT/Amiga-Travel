<?php

namespace App\Livewire;

use App\Models\Transaction;
use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Log;
use Throwable;

class PaymentProof extends Component
{
    use WithFileUploads;

    public Transaction $transaction;

    public string $reference_number = '';
    public $proof;

    public bool $showThankYou = false;
    public int $uploadProgress = 0;
    public bool $isUploading = false;

    protected $rules = [
        'reference_number' => 'required|string',
        'proof' => 'required|image|max:2048',
    ];

    public function mount(): void
    {
        $this->showThankYou = filled($this->transaction->proof_of_payment);
    }

    public function updatedProof(): void
    {
        $this->isUploading = false;
        $this->uploadProgress = 0;
    }
    
    // Livewire will automatically update $uploadProgress when using file uploads!

    public function submitProof(): void
    {
        $this->isUploading = true;
        $this->uploadProgress = 0;
        $this->validate();

        // Compress the image before storing it!
        $filePath = $this->proof->path();

        // If GD is not available on the host, skip compression and store the original file.
        if (! extension_loaded('gd')) {
            Log::warning('GD extension not available — skipping image compression and storing original proof file.', [
                'transaction_id' => $this->transaction->id ?? null,
            ]);
            $path = $this->proof->store('proofs', 'public');
        } else {
            $imageInfo = getimagesize($filePath);

            if ($imageInfo) {
                $mimeType = $imageInfo['mime'];

                // Create an image resource from the uploaded file only if the driver supports it.
                $image = null;

                if (in_array($mimeType, ['image/jpeg', 'image/jpg'], true) && function_exists('imagecreatefromjpeg')) {
                    $image = imagecreatefromjpeg($filePath);
                } elseif ($mimeType === 'image/png' && function_exists('imagecreatefrompng')) {
                    $image = imagecreatefrompng($filePath);
                } elseif ($mimeType === 'image/gif' && function_exists('imagecreatefromgif')) {
                    $image = imagecreatefromgif($filePath);
                } elseif ($mimeType === 'image/webp' && function_exists('imagecreatefromwebp')) {
                    $image = imagecreatefromwebp($filePath);
                }

                if ($image) {
                // Resize if too big (max width 1920px)
                $maxWidth = 1920;
                $originalWidth = $imageInfo[0];
                $originalHeight = $imageInfo[1];
                
                if ($originalWidth > $maxWidth) {
                    $newWidth = $maxWidth;
                    $newHeight = (int) round(($originalHeight / $originalWidth) * $newWidth);
                    $resized = imagecreatetruecolor($newWidth, $newHeight);
                    
                    // Preserve transparency for PNGs and GIFs
                    if (in_array($mimeType, ['image/png', 'image/gif'])) {
                        imagealphablending($resized, false);
                        imagesavealpha($resized, true);
                        $transparent = imagecolorallocatealpha($resized, 255, 255, 255, 127);
                        imagefilledrectangle($resized, 0, 0, $newWidth, $newHeight, $transparent);
                    }
                    
                    imagecopyresampled($resized, $image, 0, 0, 0, 0, $newWidth, $newHeight, $originalWidth, $originalHeight);
                    imagedestroy($image);
                    $image = $resized;
                }
                
                // Save compressed image to temp file
                $tempFile = tempnam(sys_get_temp_dir(), 'proof');
                
                switch ($mimeType) {
                    case 'image/jpeg':
                    case 'image/jpg':
                        imagejpeg($image, $tempFile, 70); // 70% quality
                        break;
                    case 'image/png':
                        imagepng($image, $tempFile, 6); // 6/9 compression
                        break;
                    case 'image/webp':
                        imagewebp($image, $tempFile, 70);
                        break;
                    case 'image/gif':
                        imagegif($image, $tempFile);
                        break;
                }
                
                imagedestroy($image);
                
                // Get file extension
                $extension = match ($mimeType) {
                    'image/jpeg', 'image/jpg' => 'jpg',
                    'image/png' => 'png',
                    'image/gif' => 'gif',
                    'image/webp' => 'webp',
                    default => $this->proof->extension(),
                };
                
                    // Generate new filename and store compressed image
                    $filename = uniqid('proof_', true) . '.' . $extension;
                    $path = \Illuminate\Support\Facades\Storage::disk('public')->putFileAs('proofs', new \Illuminate\Http\File($tempFile), $filename);

                    // Delete temp file
                    unlink($tempFile);
                } else {
                    // Fall back to original if compression failed
                    Log::warning('Image compression failed — storing original uploaded proof.', [
                        'transaction_id' => $this->transaction->id ?? null,
                        'file' => $this->proof->getClientOriginalName(),
                    ]);
                    $path = $this->proof->store('proofs', 'public');
                }
            } else {
                // Fall back to original if not an image
                $path = $this->proof->store('proofs', 'public');
            }
        }

        $this->transaction->update([
            'proof_of_payment' => $path,
            'payment_status' => 'pending',
            'payment_reference' => $this->reference_number,
        ]);

        try {
            \Illuminate\Support\Facades\Mail::to($this->transaction->booking->client_email)
                ->queue(new \App\Mail\PaymentProofReceived($this->transaction));
        } catch (Throwable $e) {
            Log::error('Failed queueing payment proof received email', [
                'transaction_id' => $this->transaction->id ?? null,
                'booking_id' => $this->transaction->booking->id ?? null,
                'email' => $this->transaction->booking->client_email ?? null,
                'error' => $e->getMessage(),
            ]);
        }

        $this->transaction->refresh();
        session(['cancellation_window_expires_for_' . $this->transaction->booking->transaction_number => now()->addMinutes(5)->timestamp]);
        $this->isUploading = false;
        $this->showThankYou = true;
    }

    public function render()
    {
        return view('livewire.payment-proof');
    }
}
