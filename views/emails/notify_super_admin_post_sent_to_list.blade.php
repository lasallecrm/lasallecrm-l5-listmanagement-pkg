post sent to list!
<hr />
{{ $data['id']['listTitle'] }}

Email from {!! $data['site_name'] !!} to notify you that a blog post was sent to a LaSalleCRM email list:
<br /><br />
Post: {!! $data['id']['title'] !!} (id #{!! $data['id']['id'] !!})
<br /><br />
LaSalleCRM List Name: {!! $data['id']['listTitle'] !!} (id #{!! $data['id']['listID'] !!})
<br /><br />
<hr>
This is an automatically generated email (Lasallecms\Lasallecmsapi\Listeners\SuperAdminNotifications)
