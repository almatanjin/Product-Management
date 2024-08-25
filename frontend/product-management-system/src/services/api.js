import axios from 'axios';
const http = axios.create({
  baseURL: 'http://127.0.0.1/api',
  headers: {
    'Content-Type': 'application/json',

  },
});

const setAuthToken = (token) => {
  if (token) {
    http.defaults.headers.common['Authorization'] = `Bearer ${token}`;
  } else {
    delete http.defaults.headers.common['Authorization'];
  }
};

// Retrieve token from localStorage and set it for Axios
const initializeAuth = () => {
  const token = localStorage.getItem('authToken');
  if (token) {
    setAuthToken(token);
  }
};

// Call this function at the entry point of your application
initializeAuth();

export default http