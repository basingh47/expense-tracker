@component('mail::message')
# Expense Report

Hi {{ auth()->user()->name }},  
Please find your attached expense report PDF.

Thanks,  
{{ config('app.name') }}
@endcomponent
