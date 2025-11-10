import api from './api';

export const getPlanets = async () => {
    try {
        const response = await api.get('/planets');
        return response.data;
    } catch (error) {
        console.error('Lỗi API:', error);
        return { success: false, message: error.message || 'Lỗi API' };
    }
};