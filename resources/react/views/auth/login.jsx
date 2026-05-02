import React, { useState } from 'react'
import {
    CButton,
    CCard,
    CCardBody,
    CCardGroup,
    CCol,
    CContainer,
    CForm,
    CFormInput,
    CInputGroup,
    CInputGroupText,
    CRow,
} from '@coreui/react'
import CIcon from '@coreui/icons-react'
import { cilLockLocked, cilUser } from '@coreui/icons'
import { toast } from 'react-toastify'
import cookies from 'js-cookie'
import axiosInstance from '../../services/axios'
import { FontAwesomeIcon } from '@fortawesome/react-fontawesome'
import { faEye, faEyeSlash } from '@fortawesome/free-solid-svg-icons'

const Login = () => {
    const [formData, setFormData] = useState({
        email: '',
        password: '',
    })

    const handleChange = (e) => {
        const { name, value } = e.target
        setFormData((prevData) => ({
            ...prevData,
            [name]: value,
        }))
    }

    const handleLogin = async (e) => {
        e.preventDefault()

        const response = new Promise(async (resolve, reject) => {
            try {
                const res = await axiosInstance.post('/auth/login', formData)
                if (res.data.error) {
                    return reject(res.data.error)
                }
                cookies.set('auth_token', res.data.data, { expires: 7 })
                setTimeout(() => {
                    window.location.href = '/'
                }, 1000)
                resolve(res.data)
            } catch (error) {
                reject(error)
            }
        })

        toast.promise(response, {
            pending: 'Logging in...',
            success: 'Login successful',
            error: 'Invalid username or password',
        })
    }

    const [showPassword, setShowPassword] = useState(false)

    return (
        <div className="min-vh-100 d-flex align-items-center">
            <CContainer>
                <CRow className="justify-content-center align-items-center">
                    <CCol md={6} lg={5} className="d-none d-md-block">
                        <div className="pe-5">
                            <h2 className="fw-semibold mb-3">{import.meta.env.VITE_APP_NAME}</h2>

                            <p className="mb-4">
                                Secure access to your business operations, inventory, and reporting
                                dashboard.
                            </p>

                            <div className="border-start border-4 ps-3">
                                <p className="mb-2">Role-based access control</p>
                                <p className="mb-2">File sharing and collaboration</p>
                                <p className="mb-2">High Performance and Scalability</p>
                            </div>

                            <div className="mt-5 small text-muted">
                                © {new Date().getFullYear()} {import.meta.env.VITE_APP_NAME}. All
                                rights reserved.
                            </div>
                        </div>
                    </CCol>

                    <CCol xs={12} md={6} lg={4}>
                        <CCard className="border-0 shadow-sm">
                            <CCardBody className="p-4 p-md-5">
                                <div className="mb-4">
                                    <h4 className="fw-semibold mb-1">Sign in</h4>
                                    <p className="text-muted mb-0 small">
                                        Enter your credentials to continue
                                    </p>
                                </div>

                                <CForm onSubmit={handleLogin}>
                                    <div className="mb-3">
                                        <label className="form-label small text-muted">
                                            Email
                                        </label>
                                        <CInputGroup>
                                            <CInputGroupText>
                                                <CIcon icon={cilUser} />
                                            </CInputGroupText>
                                            <CFormInput
                                                value={formData.email}
                                                onChange={handleChange}
                                                name="email"
                                                type="email"
                                                placeholder="user@company.com"
                                                className="shadow-none"
                                                required
                                            />
                                        </CInputGroup>
                                    </div>

                                    <div className="mb-4">
                                        <label className="form-label small text-muted">
                                            Password
                                        </label>
                                        <CInputGroup>
                                            <CInputGroupText>
                                                <CIcon icon={cilLockLocked} />
                                            </CInputGroupText>

                                            <CFormInput
                                                value={formData.password}
                                                onChange={handleChange}
                                                name="password"
                                                type={showPassword ? 'text' : 'password'}
                                                placeholder="••••••••"
                                                className="shadow-none"
                                                required
                                            />

                                            <CInputGroupText
                                                style={{ cursor: 'pointer' }}
                                                onClick={() => setShowPassword((prev) => !prev)}
                                            >
                                                <FontAwesomeIcon
                                                    icon={showPassword ? faEyeSlash : faEye}
                                                />
                                            </CInputGroupText>
                                        </CInputGroup>
                                    </div>

                                    <CButton type="submit" color="primary" className="w-100 py-2">
                                        Sign in
                                    </CButton>
                                </CForm>
                            </CCardBody>
                        </CCard>
                    </CCol>
                </CRow>
            </CContainer>
        </div>
    )
}

export default Login
