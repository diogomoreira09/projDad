const categories = ['c', 'p', 'o', 'e']; // Card categories
const imageCount = 10; // Number of images in each category

export const generateBoard = (rows, cols) => {
  const totalCards = rows * cols;
  const totalPairs = totalCards / 2; // Total pairs of cards needed
  const cardImages = [];

  // Generate pairs of images for the board
  for (let i = 0; i < totalPairs; i++) {
    const category = categories[Math.floor(Math.random() * categories.length)];
    const number = Math.floor(Math.random() * imageCount) + 1;
    const image = `${category}${number}.png`;

    cardImages.push(image, image); // Push image twice to make a pair
  }

  // Shuffle the images to randomize their positions on the board
  cardImages.sort(() => Math.random() - 0.5);

  // Create a 2D board array with shuffled card images
  const board = [];
  let index = 0;
  for (let i = 0; i < rows; i++) {
    const row = [];
    for (let j = 0; j < cols; j++) {
      row.push({
        image: cardImages[index],
        matched: false, // Initially, no card is matched
      });
      index++;
    }
    board.push(row);
  }

  return board;
};
