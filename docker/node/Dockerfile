FROM node:20-alpine

# Set working directory
WORKDIR /app

# Install bash and git for development
RUN apk add --no-cache bash git

# Global packages for development
RUN npm install -g npm@latest vite vue-tsc

# Expose ports
EXPOSE 3000
EXPOSE 5173

# Default command
CMD ["npm", "run", "dev", "--", "--host", "0.0.0.0"]
