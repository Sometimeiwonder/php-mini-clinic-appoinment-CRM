<h1>Login</h1>
<p style="color:#6b7280; margin-bottom:16px;">Sau khi login đúng: session_regenerate_id(true), set session user, flash success, redirect /dashboard.</p>

<div style="display:flex; gap:32px; align-items:flex-start;">
<div style="flex:1;">
<form method="post" action="/login" class="card form-card">
    <?= csrf_field() ?>

    <label>Email</label>
    <input type="email" name="email" value="<?= e($old['email'] ?? '') ?>" required autofocus>

    <label>Password</label>
    <div style="position:relative;">
        <input type="password" name="password" id="password" required style="padding-right:40px;">
        <button type="button" onclick="togglePassword()" style="position:absolute; right:8px; top:50%; transform:translateY(-50%); background:none; border:none; cursor:pointer; font-size:18px; color:#6b7280; padding:4px;">&#128065;</button>
    </div>

    <div style="margin: 8px 0 16px;">
        <label style="display:inline-flex; align-items:center; gap:8px; cursor:pointer; font-weight:normal;">
            <input type="checkbox" name="remember" value="1" style="width:auto;">
            Remember me
        </label>
        <br><small style="color:#9ca3af;">Không lưu password trong cookie; chỉ giới thiệu rủi ro và token nhớ đăng nhập.</small>
    </div>

    <?php if (($_GET['msg'] ?? '') === 'logout'): ?>
        <p style="color:#16a34a; background:#f0fdf4; border:1px solid #bbf7d0; padding:10px; border-radius:6px; margin-bottom:12px;">Đăng xuất thành công.</p>
    <?php endif; ?>

    <?php if (!empty($errors['general'])): ?>
        <p class="error"><?= e($errors['general']) ?></p>
    <?php endif; ?>

    <button class="btn primary" type="submit">Login</button>
</form>
</div>

<div class="info-box">
    <h3>Secure flow</h3>
    <ol style="margin:0; padding-left:20px; line-height:2;">
        <li>Read POST safely</li>
        <li>Server-side validation</li>
        <li>Verify password_hash</li>
        <li>session_regenerate_id(true)</li>
        <li>Set session user</li>
        <li>Flash + redirect PRG</li>
    </ol>
</div>
</div>
