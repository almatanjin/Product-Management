import { createRouter, createWebHistory } from 'vue-router';
import ProductCreateView from '../views/Product/CreateView.vue';
import LoginView from '../views/LoginView.vue';
import SingUpView from '../views/SignUp/SingUpView.vue';
import ProductView from '@/views/ProductView.vue';
import CustomerView from '@/views/CustomerView.vue';
import OrderView from '@/views/OrderView.vue';
import CreateCustomerView from '@/views/Customer/CreateView.vue';
import EditCustomerView from '@/views/Customer/EditView.vue';
import CreateOrderView from '@/views/Order/CreateView.vue';
import EditProductView from '@/views/Product/EditView.vue';
import EditOrderView from '@/views/Order/EditOrder.vue';
import ProductOrderView from '@/views/ProductOrderView.vue';

const routes = [
  {
    path: '/orders',
    name: 'orders',
    component: OrderView,
    meta: { requiresAuth: true } 
  },
  {
    path: '/login',
    name: 'login',
    component: LoginView
  },
  {
    path: '/register',
    name: 'register',
    component: SingUpView
  },
  {
    path: '/create-product',
    name: 'createProduct',
    component: ProductCreateView,
    meta: { requiresAuth: true } 
  },
  {
    path: '/',
    name: 'products',
    component: ProductView, 
    meta: { requiresAuth: true } 
  },
  {
    path: '/customers',
    name: 'customers',
    component: CustomerView, 
    meta: { requiresAuth: true } 

  },

  {
    path: '/customer-create',
    name: 'customer-create',
    component: CreateCustomerView, 
    meta: { requiresAuth: true } 

  },
  {
    path: '/order-create',
    name: 'order-create',
    component: CreateOrderView, 
    meta: { requiresAuth: true } 

  },
  {
    path: '/order-edit/:id',
    name: 'order-edit',
    component: EditOrderView, 
    meta: { requiresAuth: true } 

  },
  {
    path: '/customer-edit/:id',
    name: 'customer-edit',
    component: EditCustomerView, 
    meta: { requiresAuth: true } 

  },
  {
    path: '/product-edit/:id',
    name: 'product-edit',
    component: EditProductView, 
    meta: { requiresAuth: true } 

  },
  {
    path: '/product-edit/:id',
    name: 'product-edit',
    component: EditProductView, 
    meta: { requiresAuth: true } 

  },
  {
    path: '/product-order/:id',
    name: 'product-order',
    component: ProductOrderView, 
    meta: { requiresAuth: true } 

  }
];

const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  routes
});

router.beforeEach((to, from, next) => {
  const isAuthenticated = localStorage.getItem('authToken'); 

  if (to.meta.requiresAuth && !isAuthenticated) {
    
    next({ name: 'login' }); 
  } else {
    next(); 
  }
});

export default router;
