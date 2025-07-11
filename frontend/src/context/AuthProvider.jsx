import { createContext, useEffect, useState } from "react";
import { useNavigate } from "react-router-dom";

export const AuthContext = createContext();

const AuthProvider = ({ children }) => {
  const [isAuthenticated, setIsAuthenticated] = useState(false);
  const [user, setUser] = useState(null);
  const [error, setError] = useState("");
  const [loading, setLoading] = useState(true);
  const navigate = useNavigate();

  useEffect(() => {
    const token = localStorage.getItem("token");
    const storedUser = localStorage.getItem("user");

    if (token && storedUser) {
      setIsAuthenticated(true);
      setUser(JSON.parse(storedUser));
    }

    setLoading(false);
  }, []);

  //save user data to localStorage
  const saveUserToStorage = (token, user) => {
    localStorage.setItem("token", token);
    localStorage.setItem("user", JSON.stringify(user));
    setIsAuthenticated(true);
    setUser(user);
  }

  const register = async (userData) => {
    try {
      const res = await fetch("http://localhost:8000/api/register", {
        method: "POST",
        headers: {
          "Content-Type": "application/json",
          Accept: "application/json",
        },
        body: JSON.stringify(userData),
      });

      const data = await res.json();

      if (!res.ok) {
        const message = data.errors? Object.values(data.errors).flat().join(", ") : data.message;
        setError(data.message || "Failed to register.");
        throw new Error(message);
      }

      if (data.token && data.user) {
        saveUserToStorage(data.token, data.user);
        navigate("/");
      } else {
        throw new Error(data.message || "Registration failed.");
      }
    } catch (error) {
      setError("An error occurred while registering. Please try again.");
      console.error(error);
    }
  };

  const login = async (loginData) => {
    setError(""); // Reset error state before login attempt
    try {
      const res = await fetch("http://localhost:8000/api/login", {
        method: "POST",
        headers: {
          "Content-Type": "application/json",
          Accept: "application/json",
        },
        body: JSON.stringify(loginData),
      });

      const data = await res.json();

      if (!res.ok) {
        throw new Error(data.message || "Login failed.");
      }

      if (data.token && data.user) {
        saveUserToStorage(data.token, data.user);
        navigate("/");
      } else {
        setError(data.message || "Login failed.");
      }
    } catch (error) {
      setError("An error occurred while logging in. Please try again.");
      console.error(error);
    }
  };

  const logout = () => {
    localStorage.removeItem("token");
    localStorage.removeItem("user");
    setIsAuthenticated(false);
    setUser(null);
    navigate("/login");
  };

  const role = user?.role || null;

  return (
    <AuthContext.Provider
      value={{
        register,
        login,
        logout,
        isAuthenticated,
        user,
        role,  //expose role to children
        error,
        loading,
      }}
    >
      {children}
    </AuthContext.Provider>
  );
};

export default AuthProvider;
