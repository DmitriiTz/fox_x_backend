<div class="chat__message message">
    <div class="message__top">
        <div class="message__ava">
            <img src="{{ asset($message->account->image) }}" alt="avatar">
            <span class="message__value">{{ getLevel($message->account) }}</span>
        </div>
        <p class="message__name @if($message->account->role_id == 4) message__name--icon @endif"><span>{{ $message->account->name }}</span><i class="@if($message->account->role_id == 4) ic-youtube @endif"></i></p>
        <span class="message__time">{{ \Carbon\Carbon::parse($message->created_at)->diffForHumans() }}</span>
    </div>
    <div class="message__body">
        <p class="message__text">{{ $message->text }}</p>
    </div>
</div>