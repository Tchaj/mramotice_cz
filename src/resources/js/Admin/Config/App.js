const obj = {
    apiUrl: "http://localhost/api/v1",
    headers: (auth = false) => {
        const token = localStorage.getItem("token");
        if (auth) {
            return {
                "Content-Type": "application/json",
                Authorization: `Bearer ${token}`
            };
        } else {
            return {
                "Content-Type": "application/json"
            };
        }
    }
};

export default obj;
