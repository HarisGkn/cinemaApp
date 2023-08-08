package net.codejava.ws;

import java.sql.Date;

public class Reservation {
    private int reservationid;
    private int userid;
    private int productid;
    private Date reservationdate;
    private String status;

    public Reservation(int reservationid, int userid, int productid, Date reservationdate, String status) {
        this.reservationid = reservationid;
        this.userid = userid;
        this.productid = productid;
        this.reservationdate = reservationdate;
        this.status = status;
    }

    public int getReservationid() {
        return reservationid;
    }

    public void setReservationid(int reservationid) {
        this.reservationid = reservationid;
    }

    public int getUserid() {
        return userid;
    }

    public void setUserid(int userid) {
        this.userid = userid;
    }

    public int getProductid() {
        return productid;
    }

    public void setProductid(int productid) {
        this.productid = productid;
    }

    public Date getReservationdate() {
        return reservationdate;
    }

    public void setReservationdate(Date reservationdate) {
        this.reservationdate = reservationdate;
    }

    public String getStatus() {
        return status;
    }

    public void setStatus(String status) {
        this.status = status;
    }

    @Override
    public String toString() {
        return "Reservation{" +
                "reservationid=" + reservationid +
                ", userid=" + userid +
                ", productid=" + productid +
                ", reservationdate=" + reservationdate +
                ", status='" + status + '\'' +
                '}';
    }
}

