<h3 class="mt-3 mb-1">Attachments</h3>
<form method="POST" action="/attachments" enctype="multipart/form-data">
    {{ csrf_field() }}
    <input type="hidden" name="transaction_type" value="{{ get_class($payload) === 'App\Models\Earning' ? 'earning' : 'spending' }}" />
    <input type="hidden" name="transaction_id" value="{{ $payload->id }}" />
    <input type="file" name="yeet" onchange="this.form.submit()" />
</form>
<div class="box">
    @if (!$payload->attachments->count())
        <div class="box__section text-center">No attachments for this transaction</div>
    @endif
    @foreach ($payload->attachments as $attachment)
        <div class="box__section row">
            <img src="{{ $attachment->yeet }}" style="max-width: 100%; max-height: 200px; border-radius: 5px; vertical-align: top;" />
            <div class="ml-2">
                <form method="POST" action="/attachments/{{ $attachment->id }}/delete">
                    {{ csrf_field() }}
                    <button class="button link">
                        <i class="fas fa-trash-alt"></i>
                    </button>
                </form>
            </div>
        </div>
    @endforeach
</div>