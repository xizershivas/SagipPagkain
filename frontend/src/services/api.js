import axios from 'axios';

const API_BASE = 'http://localhost:5198/api';

const api = axios.create({ baseURL: API_BASE });

api.interceptors.request.use(config => {
  const token = localStorage.getItem('token');
  if (token) config.headers.Authorization = `Bearer ${token}`;
  return config;
});

export const authService = {
  login: (data) => api.post('/auth/login', data),
  register: (formData) => api.post('/auth/register', formData, {
    headers: { 'Content-Type': 'multipart/form-data' }
  }),
};

export const lookupService = {
  getItems: () => api.get('/lookup/items'),
  getCategories: () => api.get('/lookup/categories'),
  getUnits: () => api.get('/lookup/units'),
  getPurposes: () => api.get('/lookup/purposes'),
  getFoodBanks: () => api.get('/lookup/foodbanks'),
  getFoodBanksByUser: (userId) => api.get(`/lookup/foodbanks/user/${userId}`),
  getRecommendedFoodBank: (itemId, userId) => api.get(`/lookup/item/${itemId}/recommended-foodbank/${userId}`),
};

export const donationService = {
  getAll: (params) => api.get('/donations', { params }),
  getById: (id) => api.get(`/donations/${id}`),
  create: (formData) => api.post('/donations', formData, {
    headers: { 'Content-Type': 'multipart/form-data' }
  }),
  update: (id, data) => api.put(`/donations/${id}`, data),
  archive: (id) => api.delete(`/donations/${id}`),
  getStats: (userId) => api.get(`/donations/stats/${userId}`),
};

export const inventoryService = {
  getAll: (params) => api.get('/inventory', { params }),
  transfer: (id, quantity) => api.put(`/inventory/${id}/transfer`, quantity),
};

export const beneficiaryService = {
  getAll: () => api.get('/beneficiary'),
  getProfile: (userId) => api.get(`/beneficiary/${userId}/profile`),
  activate: (id) => api.put(`/beneficiary/${id}/activate`),
  delete: (id) => api.delete(`/beneficiary/${id}`),
  getRequests: (params) => api.get('/beneficiary/requests', { params }),
  submitRequest: (formData) => api.post('/beneficiary/requests', formData, {
    headers: { 'Content-Type': 'multipart/form-data' }
  }),
  updateRequestStatus: (id, status) => api.put(`/beneficiary/requests/${id}/status`, JSON.stringify(status), {
    headers: { 'Content-Type': 'application/json' }
  }),
  deleteRequest: (id) => api.delete(`/beneficiary/requests/${id}`),
};

export const volunteerService = {
  getAll: () => api.get('/volunteers'),
  getById: (id) => api.get(`/volunteers/${id}`),
  create: (formData) => api.post('/volunteers', formData, {
    headers: { 'Content-Type': 'multipart/form-data' }
  }),
  update: (id, formData) => api.put(`/volunteers/${id}`, formData, {
    headers: { 'Content-Type': 'multipart/form-data' }
  }),
  delete: (id) => api.delete(`/volunteers/${id}`),
};

export const notificationService = {
  getUnseen: () => api.get('/notifications'),
  markSeen: (id) => api.put(`/notifications/${id}/seen`),
  markAllSeen: () => api.put('/notifications/mark-all-seen'),
};

export default api;
