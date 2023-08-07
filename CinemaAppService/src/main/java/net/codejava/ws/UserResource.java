package net.codejava.ws;

import javax.ws.rs.*;
import javax.ws.rs.core.MediaType;
import javax.ws.rs.core.Response;
import javax.servlet.http.HttpServletRequest;
import javax.servlet.http.HttpSession;
import javax.ws.rs.core.Context;

@Path("/user")
public class UserResource {

    @Context
    private HttpServletRequest request; // Inject the HttpServletRequest object

    private UserDAO userDAO; 

    public UserResource() {
        // Initialize your UserDAO instance here
        userDAO = new UserDAO(); // Replace this with actual initialization code
    }

    @GET
    @Path("/{userid}")
    @Produces(MediaType.APPLICATION_JSON)
    public Response getUserById(@PathParam("userid") int userId) {
        int authenticatedUserId = getAuthenticatedUserId(); // Replace with actual method

        if (userId == authenticatedUserId) {
            User user = userDAO.getUserById(userId);
            if (user != null) {
                return Response.ok(user).build();
            } else {
                return Response.status(Response.Status.NOT_FOUND).build();
            }
        } else {
            return Response.status(Response.Status.UNAUTHORIZED).build();
        }
    }

    @PUT
    @Path("/{userid}")
    @Consumes(MediaType.APPLICATION_JSON)
    @Produces(MediaType.APPLICATION_JSON)
    public Response updateUser(@PathParam("userid") int userId, User updatedUser) {
        int authenticatedUserId = getAuthenticatedUserId(); // Replace with actual method

        if (userId == authenticatedUserId) {
            // Fetch the user from the database
            User existingUser = userDAO.getUserById(userId);
            if (existingUser != null) {
                // Update user's information
                existingUser.setFirstName(updatedUser.getFirstName());
                existingUser.setLastName(updatedUser.getLastName());
                existingUser.setCountry(updatedUser.getCountry());
                existingUser.setCity(updatedUser.getCity());
                existingUser.setAddress(updatedUser.getAddress());
                existingUser.setEmail(updatedUser.getEmail());

                // Update user data in the database using the userDAO
                boolean success = userDAO.updateUser(existingUser);
                if (success) {
                    return Response.ok(existingUser).build();
                } else {
                    return Response.status(Response.Status.INTERNAL_SERVER_ERROR).build();
                }
            } else {
                return Response.status(Response.Status.NOT_FOUND).build();
            }
        } else {
            return Response.status(Response.Status.UNAUTHORIZED).build();
        }
    }
    
    private int getAuthenticatedUserId() {
        HttpSession session = request.getSession();
        if (session != null && session.getAttribute("userid") != null) {
            return (int) session.getAttribute("userid");
        } else {
            throw new UnauthorizedAccessException("User is not authenticated.");
        }
    }
}
