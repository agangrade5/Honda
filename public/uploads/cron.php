SERVER -&gt; CLIENT: 220 smtp14.relay.ord1a.emailsrvr.com ESMTP - VA Code Section 18.2-152.3:1 forbids use of this system for unsolicited bulk electronic mail (Spam)<br>
CLIENT -&gt; SERVER: EHLO dev.kickstartuser.com<br>
SERVER -&gt; CLIENT: 250-smtp14.relay.ord1a.emailsrvr.com250-SIZE 75000000250-STARTTLS250-AUTH PLAIN LOGIN250-AUTH=PLAIN LOGIN250-ENHANCEDSTATUSCODES250 STARTTLS<br>
CLIENT -&gt; SERVER: AUTH LOGIN<br>
SERVER -&gt; CLIENT: 334 VXNlcm5hbWU6<br>
CLIENT -&gt; SERVER: cmV3YXJkc0BuY29tcGFzc21rdC5jb20=<br>
SERVER -&gt; CLIENT: 334 UGFzc3dvcmQ6<br>
CLIENT -&gt; SERVER: RGF2aWQyMDEy<br>
SERVER -&gt; CLIENT: 235 2.7.0 Authentication successful<br>
CLIENT -&gt; SERVER: MAIL FROM:&lt;root@localhost&gt;<br>
SERVER -&gt; CLIENT: 250 2.1.0 Ok<br>
CLIENT -&gt; SERVER: RCPT TO:&lt;vikramraghuwanshi20@gmail.com&gt;<br>
SERVER -&gt; CLIENT: 250 2.1.5 Ok<br>
CLIENT -&gt; SERVER: DATA<br>
SERVER -&gt; CLIENT: 354 End data with &lt;CR&gt;&lt;LF&gt;.&lt;CR&gt;&lt;LF&gt;<br>
CLIENT -&gt; SERVER: Date: Thu, 25 Jun 2015 04:36:40 -0700<br>
CLIENT -&gt; SERVER: To: chris &lt;vikramraghuwanshi20@gmail.com&gt;<br>
CLIENT -&gt; SERVER: From: Root User &lt;root@localhost&gt;<br>
CLIENT -&gt; SERVER: Subject: CASHLESS Automated Nightly Report -<br>
CLIENT -&gt; SERVER: Message-ID: &lt;a112a82d912b1805dbb194cf13ee0a82@dev.kickstartuser.com&gt;<br>
CLIENT -&gt; SERVER: X-Priority: 3<br>
CLIENT -&gt; SERVER: X-Mailer: PHPMailer 5.2.9 (https://github.com/PHPMailer/PHPMailer/)<br>
CLIENT -&gt; SERVER: MIME-Version: 1.0<br>
CLIENT -&gt; SERVER: Content-Type: multipart/alternative;<br>
CLIENT -&gt; SERVER: 	boundary=&quot;b1_a112a82d912b1805dbb194cf13ee0a82&quot;<br>
CLIENT -&gt; SERVER: Content-Transfer-Encoding: 8bit<br>
CLIENT -&gt; SERVER: <br>
CLIENT -&gt; SERVER: --b1_a112a82d912b1805dbb194cf13ee0a82<br>
CLIENT -&gt; SERVER: Content-Type: text/plain; charset=us-ascii<br>
CLIENT -&gt; SERVER: <br>
CLIENT -&gt; SERVER: This is a plain-text message body<br>
CLIENT -&gt; SERVER: <br>
CLIENT -&gt; SERVER: <br>
CLIENT -&gt; SERVER: --b1_a112a82d912b1805dbb194cf13ee0a82<br>
CLIENT -&gt; SERVER: Content-Type: text/html; charset=us-ascii<br>
CLIENT -&gt; SERVER: <br>
CLIENT -&gt; SERVER: Please find the attached report<br>
CLIENT -&gt; SERVER: <br>
CLIENT -&gt; SERVER: <br>
CLIENT -&gt; SERVER: <br>
CLIENT -&gt; SERVER: --b1_a112a82d912b1805dbb194cf13ee0a82--<br>
CLIENT -&gt; SERVER: <br>
CLIENT -&gt; SERVER: .<br>
SERVER -&gt; CLIENT: 250 2.0.0 Ok: queued as A08408017F<br>
CLIENT -&gt; SERVER: QUIT<br>
SERVER -&gt; CLIENT: 221 2.0.0 Bye<br>
Message sent!

