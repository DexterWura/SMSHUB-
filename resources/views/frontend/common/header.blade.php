<link rel="preconnect" href="https://fonts.gstatic.com">
<link href="https://fonts.googleapis.com/css2?family={{ str_replace(' ', '+', config('app.settings.font_family.head', 'Poppins')) }}:wght@400;600;700&display=swap" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family={{ str_replace(' ', '+', config('app.settings.font_family.body', 'Poppins')) }}:wght@400;600&display=swap" rel="stylesheet">
<style>
:root {
  --head-font: "{{ config('app.settings.font_family.head', 'Poppins') }}";
  --body-font: "{{ config('app.settings.font_family.body', 'Poppins') }}";
}
.bg-primary {
  background-color: {{ config('app.settings.colors.primary') }};
}
.text-primary {
  color: {{ config('app.settings.colors.primary') }};
}
.border-primary {
  border-color: {{ config('app.settings.colors.primary') }};
}
.bg-secondary {
  background-color: {{ config('app.settings.colors.secondary') }};
}
.text-secondary {
  color: {{ config('app.settings.colors.secondary') }};
}
.border-secondary {
  border-color: {{ config('app.settings.colors.secondary') }};
}
.bg-tertiary {
  background-color: {{ config('app.settings.colors.tertiary') }};
}
.text-tertiary {
  color: {{ config('app.settings.colors.tertiary') }};
}
.border-tertiary {
  border-color: {{ config('app.settings.colors.tertiary') }};
}
input:checked~.toggle-path {
  background-color: {{ config('app.settings.colors.primary') }};
}
</style>