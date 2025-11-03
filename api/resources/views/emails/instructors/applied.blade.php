@component('mail::layout')
{{-- Header --}}
@slot('header')
    @component('mail::header', ['url' => config('app.url')])
        <img src="{{ asset('images/logo-learnsphere.png') }}" alt="LearnSphere" height="48" style="height:48px;">
    @endcomponent
@endslot

{{-- Body --}}
# 👋 New Instructor Application Received

A new instructor has just applied to join **LearnSphere**!

---

### 👤 Instructor Details
- **Name:** {{ $instructor->name }}
- **Email:** [{{ $instructor->email }}](mailto:{{ $instructor->email }})
- **Expertise:** {{ $instructor->expertise }}
@if(!empty($instructor->teaching_experience))
- **Experience:** {{ $instructor->teaching_experience }} years
@endif

@if(!empty($instructor->linkedin_url))
- **LinkedIn:** [View Profile]({{ $instructor->linkedin_url }})
@endif
@if(!empty($instructor->portfolio_url))
- **Portfolio:** [View Portfolio]({{ $instructor->portfolio_url }})
@endif

---

@component('mail::panel')
> "{{ $instructor->bio }}"
@endcomponent

@component('mail::button', ['url' => config('app.url') . '/admin/users/' . $instructor->id, 'color' => 'purple'])
👀 Review Application
@endcomponent

---

Thanks,
**The LearnSphere Team**
<small style="color:#999;">Empowering Modern Learning Experiences</small>

{{-- Footer --}}
@slot('footer')
    @component('mail::footer')
        © {{ date('Y') }} LearnSphere. All rights reserved.
        <br>
        <a href="{{ config('app.url') }}" style="color:#2F327D; text-decoration:none;">Visit Dashboard</a>
    @endcomponent
@endslot
@endcomponent
