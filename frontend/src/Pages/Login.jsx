import React, { useContext, useState } from "react";
import { Link } from "react-router-dom";
import { AuthContext } from "../context/AuthProvider";
import { toast } from "react-toastify";

const Login = () => {
  const { login, error } = useContext(AuthContext);
  const [formData, setFormData] = useState({
    email: "",
    password: "",
  });

  const [loading, setLoading] = useState(false);

  const handleChange = (e) => {
    setFormData({ ...formData, [e.target.name]: e.target.value });
  };

  const handleSubmit = async (e) => {
    e.preventDefault();
    
    //simple validation
    if (formData.email.trim() === "" || formData.password.trim() === "") {
      toast.error("All fields are required.");
      return;
    }

    if (!/\S+@\S+\.\S+/.test(formData.email)) {
      toast.error("Please enter a valid email address.");
      return;
    }
    if (formData.password.length < 8) {
      toast.error("Password must be at least 8 characters.");
      return;
    }

    setLoading(true);

    try{
      await login(formData);
    }
    catch (error) {
      toast.error(error.message || "Login failed. Please try again.");
    } finally {
      setLoading(false);
    }
  };

  return (
    <div className="w-full bg-gradient-to-br from-pink-50 to-blue-50">
      <div className="max-w-[1200px] mx-auto flex justify-center items-center py-24">
        <form className="w-[500px]" onSubmit={handleSubmit}>
          {error && (
            <p className="border py-2 rounded-md text-center text-red-500">
              {error}
            </p>
          )}
          <label htmlFor="" className="text-gray-700">
            Email
          </label>
          <input
            type="email"
            name="email"
            value={formData.email}
            onChange={handleChange}
            disabled={loading}
            className="w-full bg-transparent border border-gray-300 rounded-sm px-3 py-1 focus:outline-blue-500 mb-3"
          />
          <label htmlFor="" className="text-gray-700">
            Password
          </label>
          <input
            type="password"
            name="password"
            value={formData.password}
            onChange={handleChange}
            disabled={loading}
            className="w-full bg-transparent border border-gray-300 rounded-sm px-3 py-1 focus:outline-blue-500 mb-3"
          />
          <button disabled={loading} className="w-full bg-blue-600 text-white py-2 rounded-md cursor-pointer">
            {loading ? "Logging in..." : "Login"}
          </button>
          <p className="text-center mt-5 text-gray-700">
            Don't have an account?{" "}
            <Link to={"/register"} className="text-blue-600 underline">
              Register
            </Link>
          </p>
        </form>
      </div>
    </div>
  );
};

export default Login;