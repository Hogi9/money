@php $fmt = fn($n) => number_format(abs($n), 0, ',', '.'); @endphp
@foreach($transactions as $tx)
    <tr class="row-hover">
        <td class="text-xs text-base-content/60 whitespace-nowrap">
            {{ $tx->date->format('d M Y') }}
        </td>
        <td>
            <div class="flex items-center gap-2">
                @if($tx->type === 'income')
                    <span class="badge badge-soft badge-success text-xs">Masuk</span>
                @elseif($tx->type === 'outcome')
                    <span class="badge badge-soft badge-error text-xs">Keluar</span>
                @else
                    <span class="badge badge-soft badge-info text-xs">Transfer</span>
                @endif
                <span class="text-sm truncate max-w-[150px]">
                    {{ $tx->transactionName?->name ?? $tx->description ?? '-' }}
                </span>
            </div>
        </td>
        <td class="text-xs text-base-content/60">{{ $tx->wallet?->name ?? '-' }}</td>
        <td class="text-right text-sm whitespace-nowrap tabular-nums">
            <span class="font-semibold {{ $tx->type === 'income' ? 'text-success' : ($tx->type === 'outcome' ? 'text-error' : 'text-info') }}">
                {{ $tx->type === 'income' ? '+' : ($tx->type === 'outcome' ? '-' : '⇄') }}Rp {{ $fmt($tx->amount) }}
            </span>
            @if ($tx->type === 'transfer' && $tx->transfer_fee > 0)
                <br><span class="text-xs text-warning">fee -Rp {{ $fmt($tx->transfer_fee) }}</span>
            @endif
        </td>
    </tr>
@endforeach
