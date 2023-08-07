package net.codejava.ws;

public class Product {
    private int productId;
    private String name;
    private String description;
    private double price;
    private String type;

    public Product(int productId, String name, String description, double price, String type) {
        this.productId = productId;
        this.name = name;
        this.description = description;
        this.price = price;
        this.type = type;
    }

    public int getProductId() {
        return productId;
    }

    public void setProductId(int productId) {
        this.productId = productId;
    }

    public String getName() {
        return name;
    }

    public void setName(String name) {
        this.name = name;
    }

    public String getDescription() {
        return description;
    }

    public void setDescription(String description) {
        this.description = description;
    }

    public double getPrice() {
        return price;
    }

    public void setPrice(double price) {
        this.price = price;
    }

    public String getType() {
        return type;
    }

    public void setType(String type) {
        this.type = type;
    }

    @Override
    public String toString() {
        return "Product [productId=" + productId + ", name=" + name + ", description=" + description + ", price="
                + price + ", type=" + type + "]";
    }
}
