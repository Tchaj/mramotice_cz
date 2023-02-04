import Request from "./RequestService";

const BLOG_CATEGORY = "admin/blog-categories";

const obj = {
    create: async data => {
        try {
            await Request.post(BLOG_CATEGORY, data, true);
            return true;
        } catch (e) {
            throw e;
        }
    },
    update: async (id, data) => {
        try {
            await Request.patch(`${BLOG_CATEGORY}/${id}`, data, true);
            return true;
        } catch (e) {
            throw e;
        }
    },
    remove: async id => {
        try {
            await Request.delete(`${BLOG_CATEGORY}/${id}`, true);
            return true;
        } catch (e) {
            throw e;
        }
    },
    list: async () => {
        try {
            const { data } = await Request.get(BLOG_CATEGORY, true);
            return data;
        } catch (e) {
            throw e;
        }
    },
    detail: async id => {
        try {
            const { data } = await Request.get(`${BLOG_CATEGORY}/${id}`, true);
            return data;
        } catch (e) {
            throw e;
        }
    },
    removePostCategory: async id => {
        try {
            return await Request.delete(`admin/blog/post/category/${id}`, true);
        } catch (e) {
            throw e;
        }
    }
};

export default obj;
