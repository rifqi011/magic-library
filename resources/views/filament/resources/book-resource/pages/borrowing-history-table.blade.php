<div class="fi-ta-ctn divide-y divide-gray-200 overflow-hidden rounded-xl bg-white shadow-sm ring-1 ring-gray-950/5 dark:divide-white/10 dark:bg-gray-900 dark:ring-white/10">
    @php
        $histories = $record->bookHistories()->with('member')->orderBy('created_at', 'desc')->get();
    @endphp

    @if ($histories->isEmpty())
        <div class="fi-ta-empty-state px-6 py-12">
            <div class="fi-ta-empty-state-content mx-auto grid max-w-lg justify-items-center text-center">
                <div class="fi-ta-empty-state-icon-ctn mb-4 rounded-full bg-gray-100 p-3 dark:bg-gray-500/20">
                    <svg class="fi-ta-empty-state-icon h-6 w-6 text-gray-500 dark:text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.042A8.967 8.967 0 006 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 016 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 016-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0018 18a8.967 8.967 0 00-6 2.292m0-14.25v14.25" />
                    </svg>
                </div>
                <h4 class="fi-ta-empty-state-heading text-base font-semibold leading-6 text-gray-950 dark:text-white">
                    No borrowing history
                </h4>
                <p class="fi-ta-empty-state-description text-sm text-gray-500 dark:text-gray-400">
                    This book has not been borrowed yet.
                </p>
            </div>
        </div>
    @else
        <div class="fi-ta-content relative divide-y divide-gray-200 overflow-x-auto dark:divide-white/10 dark:border-t-white/10">
            <table class="fi-ta-table w-full table-auto divide-y divide-gray-200 text-start dark:divide-white/5">
                <thead class="divide-y divide-gray-200 dark:divide-white/5">
                    <tr class="bg-gray-50 dark:bg-white/5">
                        <th class="fi-ta-header-cell px-3 py-3.5 sm:first-of-type:ps-6 sm:last-of-type:pe-6">
                            <span class="group flex w-full items-center justify-start gap-x-1 whitespace-nowrap">
                                <span class="fi-ta-header-cell-label text-sm font-semibold text-gray-950 dark:text-white">
                                    Member
                                </span>
                            </span>
                        </th>
                        <th class="fi-ta-header-cell px-3 py-3.5 sm:first-of-type:ps-6 sm:last-of-type:pe-6">
                            <span class="group flex w-full items-center justify-start gap-x-1 whitespace-nowrap">
                                <span class="fi-ta-header-cell-label text-sm font-semibold text-gray-950 dark:text-white">
                                    Borrow Date
                                </span>
                            </span>
                        </th>
                        <th class="fi-ta-header-cell px-3 py-3.5 sm:first-of-type:ps-6 sm:last-of-type:pe-6">
                            <span class="group flex w-full items-center justify-start gap-x-1 whitespace-nowrap">
                                <span class="fi-ta-header-cell-label text-sm font-semibold text-gray-950 dark:text-white">
                                    Return Date
                                </span>
                            </span>
                        </th>
                        <th class="fi-ta-header-cell px-3 py-3.5 sm:first-of-type:ps-6 sm:last-of-type:pe-6">
                            <span class="group flex w-full items-center justify-start gap-x-1 whitespace-nowrap">
                                <span class="fi-ta-header-cell-label text-sm font-semibold text-gray-950 dark:text-white">
                                    Duration
                                </span>
                            </span>
                        </th>
                        <th class="fi-ta-header-cell px-3 py-3.5 sm:first-of-type:ps-6 sm:last-of-type:pe-6">
                            <span class="group flex w-full items-center justify-start gap-x-1 whitespace-nowrap">
                                <span class="fi-ta-header-cell-label text-sm font-semibold text-gray-950 dark:text-white">
                                    Status
                                </span>
                            </span>
                        </th>
                        <th class="fi-ta-header-cell px-3 py-3.5 sm:first-of-type:ps-6 sm:last-of-type:pe-6">
                            <span class="group flex w-full items-center justify-start gap-x-1 whitespace-nowrap">
                                <span class="fi-ta-header-cell-label text-sm font-semibold text-gray-950 dark:text-white">
                                    Fine
                                </span>
                            </span>
                        </th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 whitespace-nowrap dark:divide-white/5">
                    @foreach ($histories as $history)
                        <tr class="fi-ta-row hover:bg-gray-50 dark:hover:bg-white/5 [@media(hover:hover)]:transition [@media(hover:hover)]:duration-75">
                            <td class="fi-ta-cell p-0 first-of-type:ps-1 last-of-type:pe-1 sm:first-of-type:ps-3 sm:last-of-type:pe-3">
                                <div class="fi-ta-col-wrp">
                                    <div class="flex w-full justify-start text-start disabled:pointer-events-none">
                                        <div class="fi-ta-text grid w-full gap-y-1 px-3 py-4">
                                            <div class="flex">
                                                <div class="flex max-w-max flex-col">
                                                    <div class="fi-ta-text-item inline-flex items-center gap-1.5">
                                                        <span class="fi-ta-text-item-label text-sm leading-6 text-gray-950 dark:text-white">
                                                            {{ $history->member->name }}
                                                        </span>
                                                    </div>
                                                    <div class="fi-ta-text-item inline-flex items-center gap-1.5">
                                                        <span class="fi-ta-text-item-label text-sm leading-6 text-gray-500 dark:text-gray-400">
                                                            {{ $history->member->email }}
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td class="fi-ta-cell p-0 first-of-type:ps-1 last-of-type:pe-1 sm:first-of-type:ps-3 sm:last-of-type:pe-3">
                                <div class="fi-ta-col-wrp">
                                    <div class="flex w-full justify-start text-start disabled:pointer-events-none">
                                        <div class="fi-ta-text grid w-full gap-y-1 px-3 py-4">
                                            <div class="flex">
                                                <div class="flex max-w-max">
                                                    <div class="fi-ta-text-item inline-flex items-center gap-1.5">
                                                        <span class="fi-ta-text-item-label text-sm leading-6 text-gray-950 dark:text-white">
                                                            {{ $history->borrow_date->format('d M Y') }}
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td class="fi-ta-cell p-0 first-of-type:ps-1 last-of-type:pe-1 sm:first-of-type:ps-3 sm:last-of-type:pe-3">
                                <div class="fi-ta-col-wrp">
                                    <div class="flex w-full justify-start text-start disabled:pointer-events-none">
                                        <div class="fi-ta-text grid w-full gap-y-1 px-3 py-4">
                                            <div class="flex">
                                                <div class="flex max-w-max">
                                                    <div class="fi-ta-text-item inline-flex items-center gap-1.5">
                                                        <span class="fi-ta-text-item-label text-sm leading-6 text-gray-950 dark:text-white">
                                                            {{ $history->return_date ? $history->return_date->format('d M Y') : '-' }}
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td class="fi-ta-cell p-0 first-of-type:ps-1 last-of-type:pe-1 sm:first-of-type:ps-3 sm:last-of-type:pe-3">
                                <div class="fi-ta-col-wrp">
                                    <div class="flex w-full justify-start text-start disabled:pointer-events-none">
                                        <div class="fi-ta-text grid w-full gap-y-1 px-3 py-4">
                                            <div class="flex">
                                                <div class="flex max-w-max">
                                                    <div class="fi-ta-text-item inline-flex items-center gap-1.5">
                                                        <span class="fi-ta-text-item-label text-sm leading-6 text-gray-950 dark:text-white">
                                                            @if ($history->return_date)
                                                                {{ $history->borrow_date->diffInDays($history->return_date) }} days
                                                            @else
                                                                -
                                                            @endif
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td class="fi-ta-cell p-0 first-of-type:ps-1 last-of-type:pe-1 sm:first-of-type:ps-3 sm:last-of-type:pe-3">
                                <div class="fi-ta-col-wrp">
                                    <div class="flex w-full justify-start text-start disabled:pointer-events-none">
                                        <div class="fi-ta-text grid w-full gap-y-1 px-3 py-4">
                                            <div class="flex">
                                                <div class="flex max-w-max">
                                                    @php
                                                        $statusConfig = [
                                                            'borrowed' => [
                                                                'color' => 'warning',
                                                                'class' => 'fi-badge-color-warning bg-warning-50 text-warning-600 ring-warning-600/10 dark:bg-warning-400/10 dark:text-warning-400 dark:ring-warning-400/30',
                                                            ],
                                                            'returned' => [
                                                                'color' => 'success',
                                                                'class' => 'fi-badge-color-success bg-success-50 text-success-600 ring-success-600/10 dark:bg-success-400/10 dark:text-success-400 dark:ring-success-400/30',
                                                            ],
                                                            'late' => [
                                                                'color' => 'danger',
                                                                'class' => 'fi-badge-color-danger bg-danger-50 text-danger-600 ring-danger-600/10 dark:bg-danger-400/10 dark:text-danger-400 dark:ring-danger-400/30',
                                                            ],
                                                        ];
                                                        $config = $statusConfig[$history->status] ?? ['class' => 'fi-badge-color-gray bg-gray-50 text-gray-600 ring-gray-600/10 dark:bg-gray-400/10 dark:text-gray-400 dark:ring-gray-400/30'];
                                                    @endphp
                                                    <div class="fi-badge {{ $config['class'] }} flex min-w-[theme(spacing.6)] items-center justify-center gap-x-1 rounded-md px-2 py-1 text-xs font-medium ring-1 ring-inset">
                                                        <span class="grid">
                                                            <span class="truncate">
                                                                {{ ucfirst($history->status) }}
                                                            </span>
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td class="fi-ta-cell p-0 first-of-type:ps-1 last-of-type:pe-1 sm:first-of-type:ps-3 sm:last-of-type:pe-3">
                                <div class="fi-ta-col-wrp">
                                    <div class="flex w-full justify-start text-start disabled:pointer-events-none">
                                        <div class="fi-ta-text grid w-full gap-y-1 px-3 py-4">
                                            <div class="flex">
                                                <div class="flex max-w-max">
                                                    <div class="fi-ta-text-item inline-flex items-center gap-1.5">
                                                        @if ($history->fine > 0)
                                                            <span class="fi-ta-text-item-label text-danger-600 dark:text-danger-400 text-sm font-medium leading-6">
                                                                Rp {{ number_format($history->fine, 0, ',', '.') }}
                                                            </span>
                                                        @else
                                                            <span class="fi-ta-text-item-label text-sm leading-6 text-gray-400 dark:text-gray-600">
                                                                -
                                                            </span>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="fi-ta-footer-ctn border-t border-gray-200 px-6 py-3 dark:border-white/10">
            <div class="flex items-center justify-between gap-x-3">
                <div class="flex items-center gap-x-3">
                    <div class="fi-ta-pagination-records text-sm text-gray-700 dark:text-gray-200">
                        <span class="font-medium">{{ $histories->count() }}</span>
                        <span>{{ $histories->count() === 1 ? 'record' : 'records' }}</span>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
