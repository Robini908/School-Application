@props([
    'title',
    'value',
    'change' => null,
    'iconClass' => 'text-blue-600',
    'bgClass' => 'bg-blue-50'
])

<div class="bg-white overflow-hidden shadow-sm rounded-lg border border-gray-200">
    <div class="p-5">
        <div class="flex items-center">
            <div class="flex-shrink-0 {{ $bgClass }} rounded-md p-3">
                <svg class="h-6 w-6 {{ $iconClass }}" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    @if($title === 'Total Subjects')
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                    @elseif($title === 'Active Classes')
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                    @else
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                    @endif
                </svg>
            </div>
            <div class="ml-5 w-0 flex-1">
                <dl>
                    <dt class="text-sm font-medium text-gray-500 truncate">{{ $title }}</dt>
                    <dd class="flex items-baseline">
                        <div class="text-2xl font-semibold text-gray-900">{{ $value }}</div>
                        @if($change !== null)
                            <div class="ml-2 flex items-baseline text-sm font-semibold {{ $change >= 0 ? 'text-green-600' : 'text-red-600' }}">
                                <svg class="self-center flex-shrink-0 h-5 w-5 {{ $change >= 0 ? 'text-green-500' : 'text-red-500' }}" fill="currentColor" viewBox="0 0 20 20" aria-hidden="true">
                                    @if($change >= 0)
                                        <path fill-rule="evenodd" d="M5.293 9.707a1 1 0 010-1.414l4-4a1 1 0 011.414 0l4 4a1 1 0 01-1.414 1.414L11 7.414V15a1 1 0 11-2 0V7.414L6.707 9.707a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                                    @else
                                        <path fill-rule="evenodd" d="M14.707 10.293a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 111.414-1.414L9 12.586V5a1 1 0 012 0v7.586l2.293-2.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                                    @endif
                                </svg>
                                <span class="sr-only">{{ $change >= 0 ? 'Increased' : 'Decreased' }} by</span>
                                {{ abs($change) }}
                            </div>
                        @endif
                    </dd>
                </dl>
            </div>
        </div>
    </div>
</div> 