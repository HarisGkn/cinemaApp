package net.codejava.ws;

import javax.ws.rs.*;
import javax.ws.rs.core.MediaType;
import javax.ws.rs.core.Response;
import java.util.List;

@Path("/products")
public class ProductResource {

    private ProductDAO productDAO;

    public ProductResource() {
        // Initialize your ProductDAO instance here
        productDAO = new ProductDAO(); // Replace this with actual initialization code
    }

    @GET
    @Produces(MediaType.APPLICATION_JSON)
    public Response getAllProducts() {
        List<Product> products = productDAO.getAllProducts();
        return Response.ok(products).build();
    }
}
