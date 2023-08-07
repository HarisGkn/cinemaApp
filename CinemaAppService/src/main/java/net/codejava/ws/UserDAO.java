package net.codejava.ws;

import java.sql.Connection;
import java.sql.DriverManager;
import java.sql.PreparedStatement;
import java.sql.ResultSet;
import java.sql.SQLException;
import java.util.ArrayList;
import java.util.List;

public class UserDAO {
    private String jdbcURL = "jdbc:mysql://127.0.0.1:3306/cinemadb";
    private String jdbcUsername = "root";
    private String jdbcPassword = "";

    private static final String SELECT_USER_BY_ID = "SELECT * FROM users WHERE userid = ?";
    private static final String UPDATE_USER = "UPDATE users SET firstname = ?, lastname = ?, country = ?, city = ?, address = ?, email = ? WHERE userid = ?";

    protected Connection getConnection() {
        Connection connection = null;
        try {
            Class.forName("com.mysql.cj.jdbc.Driver");
            connection = DriverManager.getConnection(jdbcURL, jdbcUsername, jdbcPassword);
        } catch (SQLException | ClassNotFoundException e) {
            e.printStackTrace();
        }
        return connection;
    }

    public User getUserById(int userId) {
        User user = null;

        try (Connection connection = getConnection();
             PreparedStatement preparedStatement = connection.prepareStatement(SELECT_USER_BY_ID)) {
            preparedStatement.setInt(1, userId);
            ResultSet resultSet = preparedStatement.executeQuery();

            if (resultSet.next()) {
                String firstName = resultSet.getString("firstname");
                String lastName = resultSet.getString("lastname");
                String country = resultSet.getString("country");
                String city = resultSet.getString("city");
                String address = resultSet.getString("address");
                String email = resultSet.getString("email");
                String username = resultSet.getString("username");
                String password = resultSet.getString("password");
                String role = resultSet.getString("role");

                user = new User(userId, firstName, lastName, country, city, address, email, username, password, role);
            }
        } catch (SQLException e) {
            e.printStackTrace();
        }
        return user;
    }

    public boolean updateUser(User updatedUser) {
        boolean success = false;

        try (Connection connection = getConnection();
             PreparedStatement preparedStatement = connection.prepareStatement(UPDATE_USER)) {
            preparedStatement.setString(1, updatedUser.getFirstName());
            preparedStatement.setString(2, updatedUser.getLastName());
            preparedStatement.setString(3, updatedUser.getCountry());
            preparedStatement.setString(4, updatedUser.getCity());
            preparedStatement.setString(5, updatedUser.getAddress());
            preparedStatement.setString(6, updatedUser.getEmail());
            preparedStatement.setInt(7, updatedUser.getUserId());

            int rowsAffected = preparedStatement.executeUpdate();
            success = rowsAffected > 0;
        } catch (SQLException e) {
            e.printStackTrace();
        }

        return success;
    }
}
