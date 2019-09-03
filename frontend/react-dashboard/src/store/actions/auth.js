import * as actionTypes from './actionTypes';
import axios from '../../axios-wp-dev-only';

import { fetchProfile } from './index';

export const authenticationStart = () => {
    return {
        type: actionTypes.AUTHENTICATION_START
    }
}

export const authenticationSuccess = (tokenData, userIdData, userNameData, userRoleData) => {
    return {
        type: actionTypes.AUTHENTICATION_SUCCESS,
        token: tokenData,
        userId: userIdData,
        userName: userNameData,
        userRole: userRoleData
    }
}

export const logout = (token) => {
    //Revoke the token
    return dispatch => {
        axios.post("/wp-json/simple-jwt-authentication/v1/token/revoke", {}, { headers: { "Authorization": "Bearer " + token } })
            .then(response => {
                localStorage.removeItem('token');
                localStorage.removeItem('expirationDate');
                localStorage.removeItem('userId');
                localStorage.removeItem('userName');
                localStorage.removeItem('userRole');
                dispatch({
                    type: actionTypes.AUTH_LOGOUT
                });
            })
            .catch(err => {
                localStorage.removeItem('token');
                localStorage.removeItem('expirationDate');
                localStorage.removeItem('userId');
                localStorage.removeItem('userName');
                localStorage.removeItem('userRole');
                dispatch({
                    type: actionTypes.AUTH_LOGOUT_WITH_ERROR,
                    error: err
                });
            })
    }
}

export const checkAuthTimeout = dateInSeconds => {
    return dispatch => {
        setTimeout(() => {
            dispatch(logout());
        }, dateInSeconds * 1000)
    }
}

export const authenticationFailed = error => {
    return {
        type: actionTypes.AUTHENTICATION_FAILED,
        error: error
    }
}


export const authenticateUser = (email, password) => {
    return dispatch => {
        dispatch(authenticationStart());
        const authData = {
            username: email,
            password: password
        }
        axios.post('/wp-json/simple-jwt-authentication/v1/token', authData)
            .then(response => {
                //default by simple jwt authentication is 7 days
                const expirationDate = new Date(response.data.token_expires * 1000);
                const token = response.data.token;
                const userId = response.data.user_id;
                const userName = response.data.username;
                const userRole = response.data.role
                localStorage.setItem('token', token);
                localStorage.setItem('userId', userId);
                localStorage.setItem('expirationDate', expirationDate);
                localStorage.setItem('userName', userName);
                localStorage.setItem('userRole', userRole);
                dispatch(authenticationSuccess(token, userId, userName, userRole));
                dispatch(checkAuthTimeout(response.data.token_expires));
                dispatch(fetchProfile(userName));
            })
            .catch(err => {
                console.log(err);
                dispatch(authenticationFailed(err));
            });
    }
}

export const checkAuthentication = () => {
    return dispatch => {
        const token = localStorage.getItem('token');
        if (!token) {
            return;
        } else {
            //validate token
            axios.post('/wp-json/simple-jwt-authentication/v1/token/validate', '', {
                headers: {
                    'Authorization': 'Bearer ' + token
                }
            })
                .then(response => {
                    const expirationDate = new Date(localStorage.getItem('expirationDate'));
                    if (expirationDate < new Date()) {
                        return;
                    }
                    const userId = localStorage.getItem('userId');
                    const userName = localStorage.getItem('userName');
                    const userRole = localStorage.getItem('userRole');
                    dispatch(authenticationSuccess(token, userId, userName, userRole));
                    //remaining seconds actually, converted to ms since checkAuthTimeout multiplies the param by 1000
                    const remainingTime = (expirationDate.getTime() - new Date().getTime()) / 1000;
                    dispatch(checkAuthTimeout(remainingTime));
                    dispatch(fetchProfile(userName));
                })
                .catch(err => {
                    //token is invalid
                    return;
                });

        }
    }
}