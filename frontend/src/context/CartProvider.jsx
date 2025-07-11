import React, { createContext, useContext, useEffect, useState } from "react";
import { ProductContext } from "./ProductProvider";
import { toast } from "react-toastify";

export const CartContext = createContext();

const CartProvider = ({ children }) => {
  const { allProducts } = useContext(ProductContext);
  const [carts, setCarts] = useState(null);
  const [totalItems, setTotalItems] = useState(0);
  const [totalPrice, setTotalPrice] = useState(0);
  const [totalAmount, setTotalAmount] = useState(0);

  const fetchCartItems = async () => {
    const token = localStorage.getItem("token");
    try {
      const response = await fetch(`http://localhost:8000/api/cart`, {
        headers: {
          "Authorization": `Bearer ${token}`,
          "Accept": "application/json",
        }
      });
      if (!response.ok) {
        throw new Error("Failed to fetch cart items");
      }

      const data = await response.json();
      setCarts(data);
      
      const totalQty = data.reduce((acc, item) => acc + item.quantity, 0);
      setTotalItems(totalQty);

      const totalPrice = data.reduce((acc, item) => {
        const price = item.price;
        const qty = item.quantity;
        const discount = item.discount ?? item.product?.discount ?? 0; // Use product discount if available

        const  finalPrice = price * qty - (price * qty * discount)/ 100;
        return acc + finalPrice;
      }, 0);

      setTotalPrice(totalPrice);
      setTotalAmount(totalPrice);
    } catch (error) {
      toast.error("Failed to fetch cart items.");
      console.log("Error fetching cart items:", error);
    }
  };

  const addToCart = async (id, qty) => {
    const token = localStorage.getItem("token");
    try{
      const existingItem = carts?.find(item => item.product_id === id);
      if (existingItem) {
        const newQty = existingItem.quantity + qty;
        await fetch(`http://localhost:8000/api/cart/${existingItem.id}`, {
          method: "PUT",
          headers: {
            "Content-Type": "application/json",
            Authorization: `Bearer ${token}`,
          },
          body: JSON.stringify({
            quantity: newQty,
          }),
        });
      } else {
        await fetch("http://localhost:8000/api/cart", {
          method: "POST",
          headers: {
            "Content-Type": "application/json",
            Authorization: `Bearer ${token}`,
          },
          body: JSON.stringify({
            product_id: id,
            quantity: qty,
          }),
        });
      }
      await fetchCartItems();
      toast.success("Product added to cart successfully!");
    } catch (error) {
      console.error("Error adding to cart:", error);
      toast.error("Failed to add product to cart.");
    }
  };

  const removeCartItem = async (id) => {
    const token = localStorage.getItem("token");
    try {
      const response = await fetch(`http://localhost:8000/api/cart/${id}`, {
        method: "DELETE",
        headers: {
          "Authorization": `Bearer ${token}`,
        },
      });
      if (!response.ok) {
        throw new Error("Failed to remove cart item");
      }
      await fetchCartItems();
      toast.success("Product removed from cart successfully!");
    } catch (error) {
      toast.error("Failed to remove product from cart.");
      console.log(error);
    }
  };

  const updateCartItem = async (id, quantity) => {
    const token = localStorage.getItem("token");
    const cartItem = carts.find((item) => item.id === id);

    try {
      const response = await fetch(`http://localhost:8000/api/cart/${id}`, {
        method: "PUT",
        headers: {
          "Content-Type": "application/json",
          "Authorization": `Bearer ${token}`,
        },
        body: JSON.stringify({
          quantity
        }),
      });
      if (!response.ok) {
        throw new Error("Failed to update cart item");
      }
      await fetchCartItems();
      toast.success("Cart item updated successfully!");
    } catch (error) {
      toast.error("Failed to update cart item.");
      console.log(error);
    }
  };

  const handleCheckout = async () => {
    const token = localStorage.getItem("token");

    try{
      const response = await fetch("http://localhost:8000/api/orders", {
        method: "POST",
        headers: {
          Authorization: `Bearer ${token}`,
          Accept: "application/json",
        },
      });

      if(!response.ok) {
        throw new Error("Failed to create order");
      }

      const data = await response.json();
      console.log("Order placed", data);
      toast.success("Order placed successfully!");

      setCarts([]);
      setTotalItems(0);
      setTotalPrice(0);
      setTotalAmount(0);
    }
    catch (error) {
      console.error("Error placing order:", error);
      toast.error("Failed to place order.");
    }
  };

  useEffect(() => {
    fetchCartItems();
  }, []);

  return (
    <>
      <CartContext.Provider
        value={{
          carts,
          totalItems,
          totalPrice,
          totalAmount,
          removeCartItem,
          updateCartItem,
          addToCart,
          handleCheckout,
        }}
      >
        {children}
      </CartContext.Provider>
    </>
  );
};

export default CartProvider;