package net.codejava.ws;

import javax.ws.rs.*;
import javax.ws.rs.core.MediaType;
import javax.ws.rs.core.Response;
import java.text.ParseException;
import java.text.SimpleDateFormat;
import java.util.Date;
import java.util.List;

@Path("/reservations")
public class ReservationResource {

    private ReservationDAO reservationDAO;

    public ReservationResource() {
        // Initialize your ReservationDAO instance here
        reservationDAO = new ReservationDAO(); // Replace this with actual initialization code
    }

    @POST
    @Path("/create")
    @Consumes(MediaType.APPLICATION_FORM_URLENCODED)
    public Response createReservation(
            @FormParam("userid") int userid,
            @FormParam("productid") int productid,
            @FormParam("reservationdate") String reservationdateStr,
            @FormParam("status") String status) {

        SimpleDateFormat dateFormat = new SimpleDateFormat("yyyy-MM-dd");
        Date reservationdate;
        try {
            reservationdate = dateFormat.parse(reservationdateStr);
        } catch (ParseException e) {
            return Response.status(Response.Status.BAD_REQUEST).entity("Invalid date format").build();
        }

        boolean success = reservationDAO.createReservation(userid, productid, reservationdate, status);

        if (success) {
            return Response.ok("Reservation created successfully").build();
        } else {
            return Response.status(Response.Status.INTERNAL_SERVER_ERROR).entity("Failed to create reservation").build();
        }
    }
    
    @GET
    @Path("/user/{userid}")
    @Produces(MediaType.APPLICATION_JSON)
    public Response getReservationsByUserId(@PathParam("userid") int userid) {
        List<Reservation> reservations = reservationDAO.getReservationsByUserId(userid);
        return Response.ok(reservations).build();
    }
}

