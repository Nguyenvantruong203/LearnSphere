@component('mail::message')
# ⚠️ Xin chào {{ $instructor->name }},

Cảm ơn bạn đã quan tâm và đăng ký trở thành **Giảng viên trên LearnSphere**.

Rất tiếc, sau khi xem xét hồ sơ, chúng tôi **chưa thể phê duyệt đơn ứng tuyển của bạn**.

@if ($reason)
> **Lý do từ chối:** {{ $reason }}
@endif

Bạn hoàn toàn có thể **chỉnh sửa và nộp lại hồ sơ** sau này nếu muốn tiếp tục tham gia chương trình giảng dạy.

@component('mail::button', ['url' => config('app.url').'/about'])
📝 Cập nhật Hồ sơ Giảng viên
@endcomponent

Trân trọng,
**Đội ngũ LearnSphere**
@endcomponent
