<?php
$conn = new mysqli("localhost", "root", "", "phq");
$conn->set_charset("utf8");
$result = $conn->query("SELECT * FROM contact_form ORDER BY created_at DESC");
?>
<?php
function isActive($page, $currentPage) {
    return $currentPage === $page ? 'active' : '';
}
?>

<table border="1" cellpadding="10">
    <tr>
        <th>Họ tên</th>
        <th>Điện thoại</th>
        <th>Email</th>
        <th>Chương trình</th>
        <th>Ghi chú</th>
        <th>Thời gian</th>
    </tr>
    <?php while($row = $result->fetch_assoc()): ?>
        <tr>
            <td><?= htmlspecialchars($row['fullname']) ?></td>
            <td><?= htmlspecialchars($row['phone']) ?></td>
            <td><?= htmlspecialchars($row['email']) ?></td>
            <td><?= htmlspecialchars($row['train_program']) ?></td>
            <td><?= htmlspecialchars($row['note']) ?></td>
            <td><?= $row['created_at'] ?></td>
        </tr>
    <?php endwhile; ?>
</table>
