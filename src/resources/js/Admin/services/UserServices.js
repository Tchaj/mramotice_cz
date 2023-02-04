import Request from "./RequestService";
import Storage from "./StorageService";

const LOGIN = "auth/login";
const FORGOTTENPASSWORD = "auth/forgotten-password";
const CHECKFORGOTTENPASSWORDCODE = "auth/forgotten-password";
const FORGOTTENPASSWORDCODE = "auth/forgotten-password";
const CURRENTUSER = "auth/me";

const GETUSERS = "users";

const obj = {
    /**
     * admin login
     */
    login: (email, password) => {
        return new Promise((resolve, reject) => {
            return Request.post(LOGIN, { email: email, password: password })
                .then(res => {
                    Storage.set("token", res.data.access_token);
                    obj.currentUser()
                        .then(user => {
                            // resolve if admin
                            console.log("user", user);
                            if (user.data.admin) {
                                console.log("admin permissions set");
                                resolve(true);
                            } else {
                                // reject on no admin permissions
                                console.log("No admin permissions");
                                reject({
                                    message: "Admin permissions required"
                                });
                            }
                        })
                        .catch(error => {
                            reject(error);
                        });
                })
                .catch(err => reject(err));
        });
    },
    /**
     * logout from admin panel
     */
    logout: () => {
        return new Promise((resolve, reject) => {
            Storage.delete("token");
            resolve(true);
        });
    },
    /**
     * forgotten password
     */
    forgottenPassword: email => {
        return new Promise((resolve, reject) => {
            return Request.post("admin/forgotten-password", { email: email })
                .then(res => resolve(res))
                .catch(err => reject(err));
        });
    },
    /**
     * check forgotten password code
     */
    checkForgottenPasswordCode: code => {
        return new Promise((resolve, reject) => {
            return Request.get(`${CHECKFORGOTTENPASSWORDCODE}/${code}`)
                .then(res => resolve(res))
                .catch(err => reject(err));
        });
    },
    /**
     * set new password
     */
    forgottenPasswordCode: (code, password) => {
        return new Promise((resolve, reject) => {
            return Request.post(`${FORGOTTENPASSWORDCODE}/${code}`, {
                password: password
            })
                .then(res => resolve(res))
                .catch(err => reject(err));
        });
    },
    /**
     * get current logged user
     */
    currentUser: () => {
        return new Promise((resolve, reject) => {
            return Request.get(CURRENTUSER, true)
                .then(res => resolve(res))
                .catch(err => reject(err));
        });
    },
    /** admin panel */
    /**
     * get all users
     */
    getUsers: (show_all = true, limit = 0, page = 1) => {
        return new Promise((resolve, reject) => {
            let url = `admin/${GETUSERS}`;
            url =
                show_all && limit == 0 && page == 1
                    ? url
                    : `${url}?limit=${limit}&page=${page}`;
            return Request.get(url, true)
                .then(res => resolve(res))
                .catch(err => reject(err));
        });
    },
    /** get single user detail */
    getUser: async id => {
        try {
            const { data } = await Request.get(`admin/users/${id}`, true);
            return data;
        } catch (e) {
            throw e;
        }
    },
    /** patch user && customer */
    patchUser: async (id, data) => {
        try {
            const { message } = await Request.patch(
                `admin/users/${id}`,
                data,
                true
            );
            return message;
        } catch (e) {
            throw e;
        }
    },
    /**remove current user */
    deleteUser: async id => {
        try {
            const r = await Request.delete(`admin/users/${id}`, true);
            return r;
        } catch (e) {
            throw e;
        }
    },
    getCustomerByMail: async mail => {
        try {
            const { data } = await Request.get(
                `admin/users/customer?email=${mail}`,
                true
            );
            return data;
        } catch (e) {
            throw e;
        }
    }
};

export default obj;
