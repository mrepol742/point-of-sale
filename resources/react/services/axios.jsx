import axios from 'axios'
import cookies from 'js-cookie'

// Paths that should not trigger a redirect on 401 or 403 responses
const excludedPaths = ['/login', '/register']

// Create an Axios instance with default configuration
const axiosInstance = axios.create({
    baseURL: '/api',
    timeout: 30000,
    headers: {
        'Content-Type': 'application/json',
        Accept: 'application/json',
    },
})

axiosInstance.interceptors.request.use(
    (config) => {
        const token = cookies.get('auth_token')
        if (token) {
            config.headers['Authorization'] = `Bearer ${token}`
        }
        return config
    },
    (error) => {
        return Promise.reject(error)
    },
)

/**
 * This interceptor checks for 401 and 403 status codes in the response.
 */
axiosInstance.interceptors.response.use(
    (response) => {
        return response
    },
    (error) => {
        if (
            [401, 403].includes(error.response?.status) &&
            !excludedPaths.includes(window.location.pathname) &&
            cookies.get('auth_token')
        ) {
            cookies.remove('auth_token')
            window.location.href = '/login'
        }
        return Promise.reject(error)
    },
)

export default axiosInstance
