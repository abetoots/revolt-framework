import * as actionTypes from './actionTypes';
import axios from '../../axios-instance';

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

export const authenticationFailed = error => {
    return {
        type: actionTypes.AUTHENTICATION_FAILED,
        error: error
    }
}

export const authenticationFailedDev = error => {
    return {
        type: actionTypes.AUTHENTICATION_FAILED_DEV,
        error: error
    }
}

export const logout = (token) => {
    //Revoke the token
    return dispatch => {
        axios.post('/wp-json/simple-jwt-authentication/v1/token/revoke', {}, { headers: { "Authorization": "Bearer " + token } })
            .then(response => {
                localStorage.removeItem('token');
                localStorage.removeItem('expirationDate');
                localStorage.removeItem('userId');
                localStorage.removeItem('userName');
                localStorage.removeItem('userRole');
                dispatch({
                    type: actionTypes.AUTH_LOGOUT,
                    error: 'Please log in again'
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
                    error: `Error: ${err}. Please log in again`
                });
            })
    }
}

export const checkTokenTimeout = (seconds, token) => {
    return dispatch => {
        setTimeout(() => {
            dispatch(logout(token));
        }, seconds * 1000)
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
                console.log(expirationDate);
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
                dispatch(checkTokenTimeout((response.data.token_expires * 1000 - new Date().getTime()) / 1000, token));
            })
            .catch(err => {
                console.log(err);
                dispatch(authenticationFailedDev('Authentication Failed -dev'));
            });
    }
}

export const checkToken = () => {
    return dispatch => {
        dispatch(authenticationStart());
        const token = localStorage.getItem('token');
        //DEV MODE
        if (window.location.hostname === 'localhost') {
            if (token) {
                axios.post('/wp-json/simple-jwt-authentication/v1/token/validate', '', {
                    headers: {
                        'Authorization': 'Bearer ' + token
                    }
                })
                    .then(response => {
                        const expirationDate = new Date(localStorage.getItem('expirationDate'));
                        if (expirationDate < new Date()) {
                            dispatch(authenticationFailed('Something\'s wrong. Try logging in again'));
                        }
                        const userId = localStorage.getItem('userId');
                        const userName = localStorage.getItem('userName');
                        const userRole = localStorage.getItem('userRole');
                        dispatch(authenticationSuccess(token, userId, userName, userRole));
                        //remaining seconds actually, converted to ms since checkTokenTimeout multiplies the param by 1000
                        const remainingTime = (expirationDate.getTime() - new Date().getTime()) / 1000;
                        dispatch(checkTokenTimeout(remainingTime, token));
                    })
                    .catch(err => {
                        dispatch(authenticationFailedDev(err));
                        dispatch(logout(token));
                    });
            } else {
                //Webpack isolated app environment
                dispatch(authenticationFailedDev('Dev mode'));
            }
            //END DEV

        } else {
            //PRODUCTION
            if (token) {
                axios.post('/wp-json/simple-jwt-authentication/v1/token/validate', '', {
                    headers: {
                        'Authorization': 'Bearer ' + token
                    }
                })
                    .then(response => {
                        const expirationDate = new Date(localStorage.getItem('expirationDate'));
                        if (expirationDate < new Date()) {
                            dispatch(authenticationFailed('Something\'s wrong. Try logging in again'));
                        }
                        const userId = localStorage.getItem('userId');
                        const userName = localStorage.getItem('userName');
                        const userRole = localStorage.getItem('userRole');
                        dispatch(authenticationSuccess(token, userId, userName, userRole));
                        //remaining seconds actually, converted to ms since checkTokenTimeout multiplies the param by 1000
                        const remainingTime = (expirationDate.getTime() - new Date().getTime()) / 1000;
                        dispatch(checkTokenTimeout(remainingTime, token));
                    })
                    .catch(err => {
                        dispatch(authenticationFailed('Something\'s wrong. Try logging in again'));
                        dispatch(logout(token));
                    });
            } else {
                dispatch(authenticationFailed('Something\'s wrong with our server. Please wait while we fix it'));
            }
            //END PRODUCTION
        }
    }
}