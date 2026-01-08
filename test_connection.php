<?php
// Test Database Connection Script
echo "<h2>🔍 Kiểm tra kết nối Database</h2>";

// Database configuration
$host = 'localhost';
$user = 'root';
$pass = '9851343a';  // Password từ file config
$dbname = 'banhang_php';

echo "<p><strong>Host:</strong> $host</p>";
echo "<p><strong>User:</strong> $user</p>";
echo "<p><strong>Database:</strong> $dbname</p>";
echo "<hr>";

// Test connection without database
$conn = @mysqli_connect($host, $user, $pass);

if (!$conn) {
    echo "<p style='color: red;'>❌ <strong>Lỗi kết nối MySQL:</strong> " . mysqli_connect_error() . "</p>";
    echo "<p>💡 <strong>Gợi ý:</strong></p>";
    echo "<ul>";
    echo "<li>Kiểm tra XAMPP MySQL đã chạy chưa</li>";
    echo "<li>Kiểm tra password MySQL (có thể password rỗng '')</li>";
    echo "</ul>";
    exit;
}

echo "<p style='color: green;'>✅ Kết nối MySQL server thành công!</p>";

// Check if database exists
$db_exists = mysqli_select_db($conn, $dbname);

if (!$db_exists) {
    echo "<p style='color: orange;'>⚠️ Database '$dbname' chưa tồn tại!</p>";
    echo "<p>💡 <strong>Cần import file:</strong> database/banhang_php.sql vào phpMyAdmin</p>";
    echo "<p>Hoặc click button bên dưới để tự động tạo:</p>";

    // Auto create database option
    if (isset($_GET['create_db'])) {
        $sql_file = __DIR__ . '/database/banhang_php.sql';
        if (file_exists($sql_file)) {
            $sql = file_get_contents($sql_file);
            if (mysqli_multi_query($conn, $sql)) {
                echo "<p style='color: green;'>✅ Đã import database thành công! Refresh lại trang.</p>";
            } else {
                echo "<p style='color: red;'>❌ Lỗi import: " . mysqli_error($conn) . "</p>";
            }
        } else {
            echo "<p style='color: red;'>❌ Không tìm thấy file SQL!</p>";
        }
    } else {
        echo "<a href='?create_db=1' style='padding: 10px 20px; background: #007bff; color: white; text-decoration: none; border-radius: 5px;'>Tự động tạo Database</a>";
    }
} else {
    echo "<p style='color: green;'>✅ Database '$dbname' đã tồn tại!</p>";

    // Test query
    mysqli_set_charset($conn, 'utf8');
    $result = mysqli_query($conn, "SELECT COUNT(*) as total FROM products");
    if ($result) {
        $row = mysqli_fetch_assoc($result);
        echo "<p style='color: green;'>✅ Có <strong>" . $row['total'] . "</strong> sản phẩm trong database!</p>";
    }

    // List tables
    $tables_result = mysqli_query($conn, "SHOW TABLES");
    echo "<h3>📋 Danh sách bảng:</h3><ul>";
    while ($table = mysqli_fetch_array($tables_result)) {
        echo "<li>" . $table[0] . "</li>";
    }
    echo "</ul>";

    echo "<hr>";
    echo "<h3>🚀 Truy cập website:</h3>";
    echo "<p><a href='cake-main/index.php' style='padding: 10px 20px; background: #28a745; color: white; text-decoration: none; border-radius: 5px; margin-right: 10px;'>🏠 Trang chủ</a>";
    echo "<a href='admin/' style='padding: 10px 20px; background: #dc3545; color: white; text-decoration: none; border-radius: 5px;'>🔐 Admin Panel</a></p>";
}

mysqli_close($conn);
?>