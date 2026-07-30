<?php
$file = 'c:\laragon\www\amiga-gracia\resources\views\livewire\booking-lookup.blade.php';
$content = file_get_contents($file);

$target1 = <<<EOT
                                                            x-data="{}"
                                                            x-init="
                                                                \$nextTick(() => {
                                                                    flatpickr(\$el.querySelector('input'), {
                                                                        dateFormat: 'Y-m-d',
                                                                        altInput: true,
                                                                        altFormat: 'F j, Y',
                                                                        minDate: 'today',
                                                                        disableMobile: true,
                                                                        onChange: function(sel, dateStr) {
                                                                            \$wire.set('rebooking_departure_date', dateStr);
                                                                        }
                                                                    });
                                                                })
                                                            "
EOT;

$replacement1 = <<<EOT
                                                            x-data="{ enabledDates: @js(\$availableRebookingDates) }"
                                                            x-init="
                                                                \$nextTick(() => {
                                                                    if (enabledDates.length === 0) {
                                                                        \$el.querySelector('input').disabled = true;
                                                                        \$el.querySelector('input').placeholder = 'No available dates';
                                                                    } else {
                                                                        flatpickr(\$el.querySelector('input'), {
                                                                            dateFormat: 'Y-m-d',
                                                                            altInput: true,
                                                                            altFormat: 'F j, Y',
                                                                            minDate: 'today',
                                                                            enable: enabledDates,
                                                                            disableMobile: true,
                                                                            onChange: function(sel, dateStr) {
                                                                                \$wire.set('rebooking_departure_date', dateStr);
                                                                            }
                                                                        });
                                                                    }
                                                                })
                                                            "
EOT;

$target2 = <<<EOT
                                                                x-data="{}"
                                                                x-init="
                                                                    \$nextTick(() => {
                                                                        flatpickr(\$el.querySelector('input'), {
                                                                            dateFormat: 'Y-m-d',
                                                                            altInput: true,
                                                                            altFormat: 'F j, Y',
                                                                            minDate: 'today',
                                                                            disableMobile: true,
                                                                            onChange: function(sel, dateStr) {
                                                                                \$wire.set('rebooking_return_date', dateStr);
                                                                            }
                                                                        });
                                                                    })
                                                                "
EOT;

$replacement2 = <<<EOT
                                                                x-data="{ enabledReturnDates: @js(\$availableRebookingReturnDates) }"
                                                                x-init="
                                                                    \$nextTick(() => {
                                                                        if (enabledReturnDates.length === 0) {
                                                                            \$el.querySelector('input').disabled = true;
                                                                            \$el.querySelector('input').placeholder = 'No available dates';
                                                                        } else {
                                                                            flatpickr(\$el.querySelector('input'), {
                                                                                dateFormat: 'Y-m-d',
                                                                                altInput: true,
                                                                                altFormat: 'F j, Y',
                                                                                minDate: 'today',
                                                                                enable: enabledReturnDates,
                                                                                disableMobile: true,
                                                                                onChange: function(sel, dateStr) {
                                                                                    \$wire.set('rebooking_return_date', dateStr);
                                                                                }
                                                                            });
                                                                        }
                                                                    })
                                                                "
EOT;

$content = str_replace($target1, $replacement1, $content, $count1);
$content = str_replace($target2, $replacement2, $content, $count2);

file_put_contents($file, $content);
echo "Replaced \$count1 and \$count2 occurrences.\n";
