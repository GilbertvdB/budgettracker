<x-mail::message>
# Hallo,

We’re excited to inform you that **{{$userEmail}}** has invited you to join them in tracking a budget for **{{$budgetTitle}}** on BudgetTracker.

To accept the invitation, please click the link below. If you’re not yet a member, you’ll have the opportunity to sign up and start collaborating in just a few steps.


<x-mail::button :url="$inviteLink" color="primary">
Accept invitation.
</x-mail::button>

If you have any questions or need assistance, please don’t hesitate to reach out to our support team.

Thank you for choosing BudgetTracker—we look forward to helping you achieve your budgeting goals!

Best regards,<br>
{{ config('app.name') }}
</x-mail::message>
