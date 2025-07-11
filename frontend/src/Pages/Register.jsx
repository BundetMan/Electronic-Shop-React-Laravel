import React, { useContext, useState } from "react";
import { Link } from "react-router-dom";
import { AuthContext } from "../context/AuthProvider";
import { toast } from "react-toastify";

const Register = () => {
  const [formData, setFormData] = useState({
    username: "",
    email: "",
    password: "",
    confirmPwd: "",
  });

  const { register } = useContext(AuthContext);

  const [loading, setLoading] = useState(false);

  const handleChange = (e) => {
    setFormData({ ...formData, [e.target.name]: e.target.value });
  };

  const handleSubmit = async (e) => {
    e.preventDefault();

    if (
      formData.username.trim() === "" ||
      formData.email.trim() === "" ||
      formData.password.trim() === "" ||
      formData.confirmPwd.trim() === ""
    ) {
      toast.error("All fields are required.");
      return;
    }

    if (formData.password !== formData.confirmPwd) {
      toast.error("Passwords do not match.");
      return;
    }

    if (formData.password.length < 8) {
      toast.error("Password must be at least 8 characters.");
      return;
    }

    if(!/\S+@\S+\.\S+/.test(formData.email)) {
      toast.error("Please enter a valid email address.");
      return;
    }

    setLoading(true);

    try {
      // Assume register returns a promise that throws on error
      await register({
        name: formData.username,
        email: formData.email,
        password: formData.password,
        password_confirmation: formData.confirmPwd,
      });
    } catch (error) {
      toast.error(error.message || "Registration failed. Please try again.");
      console.error("Registration error:", error);
    } finally {
      setLoading(false);
    }
  };

  return (
    <div className="w-full bg-gradient-to-br from-pink-50 to-blue-50">
      <div className="max-w-[1200px] mx-auto flex justify-center items-center py-10">
        <form className="w-[500px]" onSubmit={handleSubmit}>
          <label className="text-gray-700">Username</label>
          <input
            type="text"
            name="username"
            value={formData.username}
            onChange={handleChange}
            className="w-full bg-transparent border-[1.5px] border-gray-300 rounded-sm px-3 py-1 focus:outline-blue-500 mb-3"
            disabled={loading}
          />

          <label className="text-gray-700">Email</label>
          <input
            type="email"
            name="email"
            value={formData.email}
            onChange={handleChange}
            className="w-full bg-transparent border border-gray-300 rounded-sm px-3 py-1 focus:outline-blue-500 mb-3"
            disabled={loading}
          />

          <label className="text-gray-700">Password</label>
          <input
            type="password"
            name="password"
            value={formData.password}
            onChange={handleChange}
            className="w-full bg-transparent border border-gray-300 rounded-sm px-3 py-1 focus:outline-blue-500 mb-3"
            disabled={loading}
          />

          <label className="text-gray-700">Confirm Password</label>
          <input
            type="password"
            name="confirmPwd"
            value={formData.confirmPwd}
            onChange={handleChange}
            className="w-full bg-transparent border border-gray-300 rounded-sm px-3 py-1 focus:outline-blue-500 mb-3"
            disabled={loading}
          />

          {errorMsg && (
            <p className="text-red-600 mb-3 font-medium">{errorMsg}</p>
          )}

          <button
            className="w-full bg-blue-600 text-white py-2 rounded-md cursor-pointer disabled:opacity-50"
            disabled={loading}
          >
            {loading ? "Registering..." : "Register"}
          </button>

          <p className="text-center mt-5 text-gray-700">
            Already have an account?{" "}
            <Link to={"/login"} className="text-blue-600 underline">
              Login
            </Link>
          </p>
        </form>
      </div>
    </div>
  );
};

export default Register;
