// gameUtils.js
export function generateCards(cols, rows) {
  const totalCards = cols * rows;
  const numPairs = totalCards / 2;
  const images = ['card1', 'card2', 'card3', 'card4', 'card5', 'card6']; // List of images
  const cards = [];

  for (let i = 0; i < numPairs; i++) {
    cards.push({ imageId: images[i % images.length], flipped: false });
    cards.push({ imageId: images[i % images.length], flipped: false });
  }

  return shuffleArray(cards);
}

export function shuffleArray(array) {
  for (let i = array.length - 1; i > 0; i--) {
    const j = Math.floor(Math.random() * (i + 1));
    [array[i], array[j]] = [array[j], array[i]];
  }
  return array;
}
