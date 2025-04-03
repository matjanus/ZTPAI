FROM node:18-alpine

WORKDIR /app

RUN apk update && apk add --no-cache \
    git \
    unzip \
    bash

COPY package.json ./

# RUN npm install
# RUN npm update  

# RUN npm install react-router-dom
# RUN npm install axios

COPY . .

# CMD ["npm", "run", "dev", "--", "--host"]
CMD bash -c "npm install && npm run dev -- --host"
# CMD "npm run dev -- --host"