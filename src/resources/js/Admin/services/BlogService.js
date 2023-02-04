import Request from "./RequestService";

const BLOG = "admin/blog";

const obj = {
    /**blog list */
    list: async () => {
        try {
            const { data } = await Request.get(BLOG, true);
            return data;
        } catch (e) {
            throw e;
        }
    },
    /**blog post detail */
    detail: async id => {
        try {
            const { data } = await Request.get(`${BLOG}/${id}`, true);
            return data;
        } catch (e) {
            throw e;
        }
    },
    /**create new blog post */
    create: async postData => {
        try {
            const { data } = await Request.post(BLOG, postData, true);
            return data;
        } catch (e) {
            throw e;
        }
    },
    /**update blog post by id */
    update: async (id, postData) => {
        try {
            const { data } = await Request.patch(`${BLOG}/${id}`, postData, true);
            return data;
        } catch (e) {
            throw e;
        }
    },
    /**delete blog post by id */
    remove: async id => {
        try {
            const { data } = await Request.delete(`${BLOG}/${id}`, true);
            return data;
        } catch (e) {
            throw e;
        }
    }
};

export default obj;
