<ul class="ref-list">
    @foreach($listReferrals as $referral)
        <li class="ref-list__item">
            <div class="ref-list__left">
                <img src="{{ asset($referral->image) }}" alt="avatar" class="ref-list__avatar">
                <p class="ref-list__name">{{ $referral->name }}</p>
            </div>
            <div class="ref-list__center">
                {{--<p class="ref-list__value">Реферальные начисления: {{ $referral->price }} coins</p>--}}
            </div>
            <div class="ref-list__right">
                <p class="ref-list__level">{{ getLevel($referral) }} LEVEL.</p>
            </div>
        </li>
    @endforeach
</ul>