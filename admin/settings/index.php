<?php
require_once '../../config/config.php';
require_once '../../config/database.php';
require_once '../includes/auth.php';

$page_title = "System Settings";

$stmt = $pdo->query("SELECT * FROM settings LIMIT 1");
$settings = $stmt->fetch(PDO::FETCH_ASSOC);

include '../includes/header.php';
include '../includes/sidebar.php';
?>

<div class="content">

<div class="container-fluid p-4">

<div class="d-flex justify-content-between align-items-center mb-4">

<div>

<h2 class="fw-bold">System Settings</h2>

<p class="text-muted">
Configure your SolveTech Academy LMS.
</p>

</div>

</div>

<form action="update.php" method="POST" enctype="multipart/form-data">

<input type="hidden" name="id" value="<?= $settings['id']; ?>">

<div class="card shadow mb-4">

<div class="card-header bg-primary text-white">

<h5 class="mb-0">Academy Information</h5>

</div>

<div class="card-body">

<div class="row">

<div class="col-md-6 mb-3">

<label>Academy Name</label>

<input
type="text"
name="academy_name"
class="form-control"
value="<?= htmlspecialchars($settings['academy_name']); ?>"
required>

</div>

<div class="col-md-6 mb-3">

<label>Email</label>

<input
type="email"
name="academy_email"
class="form-control"
value="<?= htmlspecialchars($settings['academy_email']); ?>">

</div>

<div class="col-md-6 mb-3">

<label>Phone</label>

<input
type="text"
name="academy_phone"
class="form-control"
value="<?= htmlspecialchars($settings['academy_phone']); ?>">

</div>

<div class="col-md-6 mb-3">

<label>Website</label>

<input
type="text"
name="website"
class="form-control"
value="<?= htmlspecialchars($settings['website']); ?>">

</div>

<div class="col-md-12 mb-3">

<label>Address</label>

<textarea
name="academy_address"
rows="3"
class="form-control"><?= htmlspecialchars($settings['academy_address']); ?></textarea>

</div>

</div>

</div>

</div>

<div class="card shadow mb-4">

<div class="card-header bg-success text-white">

<h5 class="mb-0">Branding</h5>

</div>

<div class="card-body">

<div class="row">

<div class="col-md-6">

<label>Academy Logo</label>

<input
type="file"
name="academy_logo"
class="form-control">

<?php if(!empty($settings['academy_logo'])): ?>

<img
src="../../uploads/settings/<?= htmlspecialchars($settings['academy_logo']); ?>"
width="150"
class="mt-3">

<?php endif; ?>

</div>

<div class="col-md-6">

<label>Favicon</label>

<input
type="file"
name="academy_favicon"
class="form-control">

<?php if(!empty($settings['academy_favicon'])): ?>

<img
src="../../uploads/settings/<?= htmlspecialchars($settings['academy_favicon']); ?>"
width="60"
class="mt-3">

<?php endif; ?>

</div>

</div>

</div>

</div>

<div class="card shadow mb-4">

<div class="card-header bg-warning">

<h5 class="mb-0">Regional Settings</h5>

</div>

<div class="card-body">

<div class="row">

<div class="col-md-6">

<label>Currency</label>

<input
type="text"
name="currency"
class="form-control"
value="<?= htmlspecialchars($settings['currency']); ?>">

</div>

<div class="col-md-6">

<label>Timezone</label>

<input
type="text"
name="timezone"
class="form-control"
value="<?= htmlspecialchars($settings['timezone']); ?>">

</div>

</div>

</div>

</div>

<div class="card shadow mb-4">

<div class="card-header bg-info text-white">

<h5 class="mb-0">SMTP Settings</h5>

</div>

<div class="card-body">

<div class="row">

<div class="col-md-6 mb-3">

<label>SMTP Host</label>

<input
type="text"
name="smtp_host"
class="form-control"
value="<?= htmlspecialchars($settings['smtp_host']); ?>">

</div>

<div class="col-md-6 mb-3">

<label>SMTP Port</label>

<input
type="number"
name="smtp_port"
class="form-control"
value="<?= htmlspecialchars($settings['smtp_port']); ?>">

</div>

<div class="col-md-6 mb-3">

<label>SMTP Username</label>

<input
type="text"
name="smtp_username"
class="form-control"
value="<?= htmlspecialchars($settings['smtp_username']); ?>">

</div>

<div class="col-md-6 mb-3">

<label>SMTP Password</label>

<input
type="password"
name="smtp_password"
class="form-control"
value="<?= htmlspecialchars($settings['smtp_password']); ?>">

</div>

<div class="col-md-6 mb-3">

<label>Encryption</label>

<select
name="smtp_encryption"
class="form-select">

<option value="tls" <?= $settings['smtp_encryption']=="tls"?"selected":""; ?>>TLS</option>

<option value="ssl" <?= $settings['smtp_encryption']=="ssl"?"selected":""; ?>>SSL</option>

</select>

</div>

<div class="col-md-6 mb-3">

<label>Sender Name</label>

<input
type="text"
name="sender_name"
class="form-control"
value="<?= htmlspecialchars($settings['sender_name']); ?>">

</div>

<div class="col-md-12">

<label>Sender Email</label>

<input
type="email"
name="sender_email"
class="form-control"
value="<?= htmlspecialchars($settings['sender_email']); ?>">

</div>

</div>

</div>

</div>

<div class="card shadow mb-4">

<div class="card-header bg-danger text-white">

<h5 class="mb-0">System</h5>

</div>

<div class="card-body">

<label>Maintenance Mode</label>

<select
name="maintenance_mode"
class="form-select">

<option value="No" <?= $settings['maintenance_mode']=="No"?"selected":""; ?>>Disabled</option>

<option value="Yes" <?= $settings['maintenance_mode']=="Yes"?"selected":""; ?>>Enabled</option>

</select>

</div>

</div>

<button
class="btn btn-primary btn-lg">

<i class="fas fa-save"></i>

Save Settings

</button>

</form>

</div>

</div>

<?php include '../includes/footer.php'; ?>