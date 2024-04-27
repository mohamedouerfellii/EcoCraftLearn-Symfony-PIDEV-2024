async function getChatGPTResponse(question) {
    const apiKey = 'sk-proj-DyzhIjCpV95F7BLoEhXaT3BlbkFJicy8MvurUiJvdC47op8Z'; 
    const apiUrl = 'https://api.openai.com/v1/completions';
    const data = {
        model: 'text-davinci-002',
        prompt: question,
        max_tokens: 150,
        temperature: 0.7
    };

    try {
        const response = await fetch(apiUrl, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Authorization': `Bearer ${apiKey}`
            },
            body: JSON.stringify(data)
        });

        if (!response.ok) {
            throw new Error('Erreur lors de l\'appel à l\'API OpenAI.');
        }

        const responseData = await response.json();
        if (responseData.choices && responseData.choices.length > 0) {
            return responseData.choices[0].text.trim();
        } else {
            throw new Error('Aucune réponse n\'a été retournée par l\'API OpenAI.');
        }
    } catch (error) {
        console.error('Erreur : ', error.message);
        return 'Erreur lors de la récupération de la réponse.';
    }
}
const question = "Quelle est la capitale de la France ?";
getChatGPTResponse(question)
    .then(response => {
        console.log('Question : ', question);
        console.log('Réponse : ', response);
    })
    .catch(error => {
        console.error('Erreur : ', error);
});
