import React, { useState, useEffect } from 'react'
import {
    CForm,
    CFormInput,
    CRow,
    CCol,
    CFormSwitch,
    CButtonGroup,
    CImage,
    CButton,
    CFormSelect,
} from '@coreui/react'
import { Helmet } from 'react-helmet'
import { toast } from 'react-toastify'
import { FontAwesomeIcon } from '@fortawesome/react-fontawesome'
import { faEdit, faPlus } from '@fortawesome/free-solid-svg-icons'
import PropTypes from 'prop-types'
import axiosInstance from '../../services/axios'

const NewUser = ({ user, setUser, onCancel, fetchUsers, setShowAppModal }) => {
    const [roles, setRoles] = useState([])
    const emptyUser = {
        first_name: '',
        middle_name: '',
        last_name: '',
        prefix: '',
        suffix: '',
        password: '',
        email: '',
        gender: '',
        phone_number: '',
        date_of_birth: '',
        address: '',
        role: '',
        type: 'add',
    }

    const handleChange = (e) => {
        const { id, value } = e.target
        setUser((prevUser) => ({
            ...prevUser,
            [id]: value,
        }))
    }

    const handleSelectChange = (e) => {
        const { id, value } = e.target
        setUser((prevUser) => ({
            ...prevUser,
            [id]: value,
        }))
    }

    const handleSubmit = (e) => {
        e.preventDefault()
        if (user.type === 'add')
            return axiosInstance
                .post('/users', user)
                .then((response) => {
                    toast.success(`${user.first_name} created successfully`)
                    setUser(emptyUser)
                    fetchUsers(0)
                    setShowAppModal(false)
                })
                .catch((error) => {
                    console.error('Error creating user:', error)
                    toast.error(error.response.data.message)
                })

        axiosInstance
            .patch(`/users/${user.ulid}`, user)
            .then((response) => {
                if (response.data.error) return toast.error(response.data.error)
                toast.success(`${user.first_name} updated successfully`)
                setUser(emptyUser)
                fetchUsers(0)
                setShowAppModal(false)
            })
            .catch((error) => {
                console.error('Error updating user:', error)
                toast.error(error.response.data.message)
            })
    }

    return (
        <CForm onSubmit={handleSubmit} className="p-2">
            <div className="d-flex align-items-center mb-3 fs-5">
                <FontAwesomeIcon icon={user.type === 'add' ? faPlus : faEdit} className="me-2" />
                {user.type === 'add' ? 'New User' : 'Edit User'}
            </div>
            <CRow>
                <CCol xs={12} md={6}>
                    <CFormSelect
                        id="prefix"
                        floatingClassName="mb-3"
                        floatingLabel="Prefix"
                        onChange={handleSelectChange}
                        value={user.prefix}
                        options={[
                            { label: 'Select a prefix', value: '' },
                            { label: 'Mr.', value: 'mr' },
                            { label: 'Ms.', value: 'ms' },
                            { label: 'Mrs.', value: 'mrs' },
                            { label: 'Dr.', value: 'dr' },
                        ]}
                        required
                    />
                </CCol>
                <CCol xs={12} md={6}>
                    <CFormInput
                        type="text"
                        id="first_name"
                        floatingClassName="mb-3"
                        floatingLabel="First Name"
                        onChange={handleChange}
                        value={user.first_name}
                        placeholder=""
                        required
                    />
                </CCol>
            </CRow>
            <CRow>
                <CCol xs={12} md={6}>
                    <CFormInput
                        type="text"
                        id="middle_name"
                        floatingClassName="mb-3"
                        floatingLabel="Middle Name (If applicable)"
                        onChange={handleChange}
                        value={user.middle_name}
                        placeholder=""
                    />
                </CCol>
                <CCol xs={12} md={6}>
                    <CFormInput
                        type="text"
                        id="last_name"
                        floatingClassName="mb-3"
                        floatingLabel="Last Name"
                        onChange={handleChange}
                        value={user.last_name}
                        placeholder=""
                        required
                    />
                </CCol>
            </CRow>
            <CRow>
                <CCol xs={12} md={6}>
                    <CFormInput
                        type="suffix"
                        id="suffix"
                        floatingClassName="mb-3"
                        floatingLabel="Suffix (Optional)"
                        onChange={handleChange}
                        value={user.suffix}
                        placeholder=""
                    />
                </CCol>
                <CCol xs={12} md={6}>
                    <CFormSelect
                        id="gender"
                        floatingClassName="mb-3"
                        floatingLabel="Gender"
                        onChange={handleSelectChange}
                        value={user.gender}
                        options={[
                            { label: 'Select a gender', value: '' },
                            { label: 'Male', value: 'male' },
                            { label: 'Female', value: 'female' },
                            { label: 'Other', value: 'other' },
                        ]}
                        required
                    />
                </CCol>
            </CRow>
            <CRow>
                <CCol xs={12} md={6}>
                    <CFormInput
                        type="email"
                        id="email"
                        floatingClassName="mb-3"
                        floatingLabel="Email"
                        onChange={handleChange}
                        value={user.email}
                        placeholder=""
                        required
                    />
                </CCol>
                <CCol xs={12} md={6}>
                    <CFormInput
                        type="text"
                        id="phone_number"
                        floatingClassName="mb-3"
                        floatingLabel="Phone Number"
                        onChange={handleChange}
                        value={user.phone_number}
                        placeholder=""
                        required
                    />
                </CCol>
            </CRow>

            <CFormInput
                type="date"
                id="date_of_birth"
                floatingClassName="mb-3"
                floatingLabel="Date of Birth"
                onChange={handleChange}
                value={user.date_of_birth}
                placeholder=""
                required
            />

            <CFormInput
                type="text"
                id="address"
                floatingClassName="mb-3"
                floatingLabel="Address"
                onChange={handleChange}
                value={user.address}
                placeholder=""
                required
            />

            <CFormSelect
                id="role"
                floatingClassName="mb-3"
                floatingLabel="Role"
                onChange={handleSelectChange}
                value={user.role}
                options={[
                    { label: 'Select a role', value: '' },
                    { label: 'Admin', value: 'admin' },
                    { label: 'Cashier', value: 'cashier' },
                    { label: 'Production', value: 'production' },
                ]}
                required
            />

            <div className="d-flex justify-content-end mt-3">
                <CButton color="secondary" className="me-2" size="sm" onClick={onCancel}>
                    Cancel
                </CButton>
                <CButton color="primary" size="sm" type="submit">
                    {user.type === 'add' ? 'Create' : 'Update'}
                </CButton>
            </div>
        </CForm>
    )
}

export default NewUser

NewUser.propTypes = {
    user: PropTypes.shape({
        id: PropTypes.number,
        name: PropTypes.string,
        email: PropTypes.string,
        phone: PropTypes.string,
        address: PropTypes.string,
        password: PropTypes.string,
        status: PropTypes.string,
        role: PropTypes.string,
        type: PropTypes.string,
    }).isRequired,
    setUser: PropTypes.func.isRequired,
    fetchUsers: PropTypes.func.isRequired,
    setShowAppModal: PropTypes.func.isRequired,
    onCancel: PropTypes.func.isRequired,
}
