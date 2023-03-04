const params = new URLSearchParams(window.location.search);
const id = params.get('id');

async function loadPost() {
    try {
        const res = await fetch(`https://jsonplaceholder.typicode.com/posts/${id}`);

        if (!res.ok) {
            throw new Error('Ошибка загрузки поста');
        }

        const post = await res.json();

        document.getElementById('post-title').textContent = post.title;
        document.getElementById('post-body').textContent = post.body;

        const commentsRes = await fetch(`https://jsonplaceholder.typicode.com/posts/${id}/comments`);

        if (!commentsRes.ok) {
            throw new Error('Ошибка загрузки комментариев');
        }

        const comments = await commentsRes.json();
        const commentsContainer = document.getElementById('comments');

        commentsContainer.innerHTML = comments.map(comment => `
            <div class="comment">
                <h4>${comment.name}</h4>
                <p><strong>${comment.email}</strong></p>
                <p>${comment.body}</p>
            </div>
        `).join('');

    } catch (error) {
        document.body.innerHTML = 'Ошибка загрузки поста';
        console.log(error);
    }
}

loadPost();