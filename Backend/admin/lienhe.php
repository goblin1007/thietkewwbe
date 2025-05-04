<?php
$conn = new mysqli("localhost", "root", "", "phq");
$conn->set_charset("utf8");

$result = $conn->query("SELECT * FROM contact_form ORDER BY created_at DESC");
?>

<style>
    .contact-wrapper {
        background: #fff;
        padding: 25px;
        border-radius: 10px;
        box-shadow: 0 3px 10px rgba(0,0,0,0.05);
    }

    .contact-title {
        font-size: 22px;
        font-weight: bold;
        margin-bottom: 20px;
        color: #2c3e50;
        border-left: 5px solid #3498db;
        padding-left: 10px;
    }

    .contact-table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 10px;
    }

    .contact-table th,
    .contact-table td {
        padding: 12px 15px;
        border-bottom: 1px solid #e0e0e0;
        text-align: left;
    }

    .contact-table th {
        background-color: #3498db;
        color: #fff;
        font-weight: 600;
    }

    .contact-table tr:hover {
        background-color: #f5f9ff;
    }
    
    .contact-table td {
        color: #2c3e50;
        font-size: 14px;
    }

    @media (max-width: 768px) {
        .contact-table th, .contact-table td {
            padding: 10px;
            font-size: 13px;
        }
    }
</style>

<div class="content-area">
    <div class="contact-wrapper">
        <div class="contact-title">📬 Danh sách liên hệ</div>

        <table class="contact-table">
            <thead>
                <tr>
                    <th>Họ tên</th>
                    <th>Điện thoại</th>
                    <th>Email</th>
                    <th>Chương trình</th>
                    <th>Ghi chú</th>
                    <th>Thời gian</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($result && $result->num_rows > 0): ?>
                    <?php while($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td><?= htmlspecialchars($row['fullname']) ?></td>
                            <td><?= htmlspecialchars($row['phone']) ?></td>
                            <td><?= htmlspecialchars($row['email']) ?></td>
                            <td><?= htmlspecialchars($row['train_program']) ?></td>
                            <td><?= nl2br(htmlspecialchars($row['note'])) ?></td>
                            <td><?= htmlspecialchars($row['created_at']) ?></td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="6" style="text-align: center;">Chưa có liên hệ nào.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
