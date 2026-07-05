<h1>404 Not Found</h1>
<p style="color:#6b7280;">Route không tồn tại hoặc gọi sai HTTP method phải trả lỗi rõ ràng.</p>

<div class="error-box">
    <h3 style="color:#991b1b; margin-top:0;">The page you requested was not found.</h3>
    <p>Trong production, không hiển thị SQLSTATE, path source code hoặc stack trace cho user.</p>
</div>

<div class="info-box">
    <h3>Developer check:</h3>
    <p>public/index.php cần có fallback 404 và xử lý 405 Method Not Allowed giống Lab03.</p>
</div>
