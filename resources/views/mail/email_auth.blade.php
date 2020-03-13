안녕하세요. <i>{{ $Email->receiverName }}</i>,
<p> 이메일 인증 을 해주세요.</p>

<p><u>아래 링크를 클릭하면 이메일 인증이 완료 됩니다.</u></p>

<div>
    <p><a href="{{ $Email->auth_url }}">이메일 인증</a></p>
</div>





감사합니다.
<br/>
<i>joojang</i>
