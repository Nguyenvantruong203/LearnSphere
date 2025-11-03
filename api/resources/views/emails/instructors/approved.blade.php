@component('mail::message')
# 🎉 Chúc mừng {{ $instructor->name }}!

Hồ sơ giảng viên của bạn trên **LearnSphere** đã được **phê duyệt thành công**.
Giờ đây bạn có thể đăng nhập, tạo khóa học và chia sẻ kiến thức của mình với hàng nghìn học viên.

@component('mail::panel')
**Thông tin tài khoản:**
- Email: {{ $instructor->email }}
- Chuyên môn: {{ $instructor->expertise }}
@endcomponent

@component('mail::button', ['url' => config('app.url').'/admin/dashboard'])
🚀 Truy cập Trang Giảng Viên
@endcomponent

Nếu có bất kỳ thắc mắc nào, hãy liên hệ đội ngũ LearnSphere để được hỗ trợ nhanh nhất.

Trân trọng,
**Đội ngũ LearnSphere**
@endcomponent
