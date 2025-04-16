<form action="process_register.php" method="POST">
    <label>Username:</label>
    <input type="text" name="username" required>
    
    <label>Password:</label>
    <input type="password" name="password" required>
    
    <label>Role:</label>
    <select name="role">
        <option value="host">Host</option>
        <option value="admin">Admin</option>
    </select>

    <button type="submit">Register</button>
</form>
