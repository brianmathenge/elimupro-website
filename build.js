const fs = require('fs');
const path = require('path');
const matter = require('gray-matter');

// Create data directory if not exists
if (!fs.existsSync('data')) {
  fs.mkdirSync('data', { recursive: true });
}

const blogDir = 'content/blog';
const posts = [];

if (fs.existsSync(blogDir)) {
  const files = fs.readdirSync(blogDir).filter(f => f.endsWith('.md'));
  
  files.forEach(file => {
    const content = fs.readFileSync(path.join(blogDir, file), 'utf8');
    const { data, content: body } = matter(content);
    
    posts.push({
      slug: data.slug || file.replace('.md', ''),
      title: data.title || 'Untitled',
      author: data.author || 'Elimupro Team',
      date: data.date || new Date().toISOString().split('T')[0],
      image: data.image || '',
      category: data.category || 'General',
      status: data.status || 'draft',
      excerpt: data.excerpt || body.substring(0, 200) + '...',
      content: body
    });
  });
}

// Sort by date, newest first
posts.sort((a, b) => new Date(b.date) - new Date(a.date));

// Write JSON file
fs.writeFileSync('data/blog-posts.json', JSON.stringify({ posts }, null, 2));

console.log(`✅ Generated blog data with ${posts.length} posts`);