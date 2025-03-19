FROM node:18-alpine

WORKDIR /app

COPY package.json ./

RUN npm install
# RUN npm update  

# RUN npm install react-router-dom
# RUN npm install axios

COPY . .

CMD ["npm", "run", "dev", "--", "--host"]