package net.codejava.ws;

import java.sql.Connection;
import java.sql.DriverManager;
import java.sql.PreparedStatement;
import java.sql.ResultSet;
import java.sql.SQLException;
import java.util.ArrayList;
import java.sql.Date;
import java.util.List;


public class ReservationDAO {
    private String jdbcURL = "jdbc:mysql://localhost:3306/cinemadb";
    private String jdbcUsername = "root";
    private String jdbcPassword = "";

    private static final String INSERT_RESERVATION = "INSERT INTO reservations (userid, productid, reservationdate, status) VALUES (?, ?, ?, ?)";
    private static final String SELECT_RESERVATIONS_BY_USERID = "SELECT * FROM reservations WHERE userid = ?";

    public boolean createReservation(int userid, int productid, java.util.Date reservationdate, String status) {
        try (Connection connection = getConnection();
             PreparedStatement preparedStatement = connection.prepareStatement(INSERT_RESERVATION)) {

            preparedStatement.setInt(1, userid);
            preparedStatement.setInt(2, productid);
            preparedStatement.setDate(3, new java.sql.Date(reservationdate.getTime()));
            preparedStatement.setString(4, status);

            int rowsAffected = preparedStatement.executeUpdate();

            return rowsAffected > 0;
        } catch (SQLException e) {
            e.printStackTrace();
            return false;
        }
    }

    public List<Reservation> getReservationsByUserId(int userid) {
        List<Reservation> reservations = new ArrayList<>();

        try (Connection connection = getConnection();
             PreparedStatement preparedStatement = connection.prepareStatement(SELECT_RESERVATIONS_BY_USERID)) {

            preparedStatement.setInt(1, userid);

            try (ResultSet resultSet = preparedStatement.executeQuery()) {
                while (resultSet.next()) {
                    int reservationid = resultSet.getInt("reservationid");
                    int productid = resultSet.getInt("productid");
                    Date reservationdate = resultSet.getDate("reservationdate");
                    String status = resultSet.getString("status");

                    Reservation reservation = new Reservation(reservationid, userid, productid, reservationdate, status);
                    reservations.add(reservation);
                }
            }
        } catch (SQLException e) {
            e.printStackTrace();
        }

        return reservations;
    }

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
}
