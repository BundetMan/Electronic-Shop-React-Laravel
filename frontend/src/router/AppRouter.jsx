import React from "react";
import { Route, Routes } from "react-router-dom";
import Login from "../Pages/Login";
import Register from "../Pages/Register"
import Home from "../pages/Home";
import NotFound from "../pages/NotFound";
import Product from "../Pages/products"
import Cart from "../Pages/Cart";
import ProductDetial from "../Pages/ProductDetial"
import AdminDashboard from "../Pages/Admin";

const AppRouter = () => {
  return (
    <>
      <Routes>
        <Route path="/login" element={<Login />} />
        <Route path="/admin/dashboard" element={<AdminDashboard/>}></Route>
        <Route path="/register" element={<Register />} />
        <Route path="/" element={<Home />} />
        <Route path="/products" element={<Product />} />
        <Route path="/cart" element={<Cart />} />
        <Route path="/products/:id" element={<ProductDetial />} />
        <Route path="*" element={<NotFound />} />
      </Routes>
    </>
  );
};

export default AppRouter;